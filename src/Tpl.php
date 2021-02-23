<?php

class TplException extends Exception
{
}

class Tpl
{
    /** @var array<string> */
    private $templateFolderList;

    /** @var array<string> */
    private $translationFolderList;

    /** @var null|string */
    private $activeSectionName;

    /** @var array<string,string> */
    private $sectionList = [];

    /** @var array<string,array> */
    private $layoutList = [];

    /** @var array<string,mixed> */
    private $templateVariables = [];

    /** @var array<string,callable> */
    private $callbackList = [];

    /** @var array<array<string,string>> */
    private $translationDataList = [];

    /**
     * @param array<string> $templateFolderList
     * @param array<string> $translationFolderList
     */
    public function __construct(array $templateFolderList, array $translationFolderList = [])
    {
        $this->templateFolderList = $templateFolderList;
        $this->translationFolderList = $translationFolderList;
    }

    /**
     * @param null|string $uiLanguage
     */
    public function setLanguage($uiLanguage)
    {
        if (null === $uiLanguage) {
            $this->translationDataList = [];

            return;
        }

        // verify whether we have this translation file available
        // NOTE: we first fetch a list of supported languages and *then* only
        // check if the requested language is available to avoid needing to
        // use crazy regexp to match language codes
        $availableLanguages = [];
        foreach ($this->translationFolderList as $translationFolder) {
            foreach (glob($translationFolder.'/*.php') as $translationFile) {
                $supportedLanguage = basename($translationFile, '.php');
                if (!in_array($supportedLanguage, $availableLanguages, true)) {
                    $availableLanguages[] = $supportedLanguage;
                }
            }
        }

        if (in_array($uiLanguage, $availableLanguages, true)) {
            // load the translation files
            foreach ($this->translationFolderList as $translationFolder) {
                $translationFile = $translationFolder.'/'.$uiLanguage.'.php';
                if (!file_exists($translationFile)) {
                    continue;
                }
                $this->translationDataList[] = include $translationFile;
            }
        }
    }

    /**
     * @param array<string,mixed> $templateVariables
     */
    public function addDefault(array $templateVariables)
    {
        $this->templateVariables = array_merge($this->templateVariables, $templateVariables);
    }

    /**
     * @param string $callbackName
     */
    public function addCallback($callbackName, callable $cb)
    {
        $this->callbackList[$callbackName] = $cb;
    }

    /**
     * @param string              $templateName
     * @param array<string,mixed> $templateVariables
     *
     * @return string
     */
    public function render($templateName, array $templateVariables = [])
    {
        $this->templateVariables = array_merge($this->templateVariables, $templateVariables);
        extract($this->templateVariables);
        ob_start();
        /** @psalm-suppress UnresolvableInclude */
        include $this->templatePath($templateName);
        $templateStr = ob_get_clean();
        if (0 === count($this->layoutList)) {
            // we have no layout defined, so simple template...
            return $templateStr;
        }

        foreach ($this->layoutList as $templateName => $templateVariables) {
            unset($this->layoutList[$templateName]);
            $templateStr .= $this->render($templateName, $templateVariables);
        }

        return $templateStr;
    }

    /**
     * @param string              $templateName
     * @param array<string,mixed> $templateVariables
     *
     * @return string
     */
    private function insert($templateName, array $templateVariables = [])
    {
        return $this->render($templateName, $templateVariables);
    }

    /**
     * @param string $sectionName
     */
    private function start($sectionName)
    {
        if (null !== $this->activeSectionName) {
            throw new TplException(sprintf('section "%s" already started', $this->activeSectionName));
        }

        $this->activeSectionName = $sectionName;
        ob_start();
    }

    /**
     * @param string $sectionName
     */
    private function stop($sectionName)
    {
        if (null === $this->activeSectionName) {
            throw new TplException('no section started');
        }

        if ($sectionName !== $this->activeSectionName) {
            throw new TplException(sprintf('attempted to end section "%s" but current section is "%s"', $sectionName, $this->activeSectionName));
        }

        $this->sectionList[$this->activeSectionName] = ob_get_clean();
        $this->activeSectionName = null;
    }

    /**
     * @param string              $layoutName
     * @param array<string,mixed> $templateVariables
     */
    private function layout($layoutName, array $templateVariables = [])
    {
        $this->layoutList[$layoutName] = $templateVariables;
    }

    /**
     * @param string $sectionName
     *
     * @return string
     */
    private function section($sectionName)
    {
        if (!array_key_exists($sectionName, $this->sectionList)) {
            throw new TplException(sprintf('section "%s" does not exist', $sectionName));
        }

        return $this->sectionList[$sectionName];
    }

    /**
     * @param string      $v
     * @param null|string $cb
     *
     * @return string
     */
    private function e($v, $cb = null)
    {
        if (null !== $cb) {
            $v = $this->batch($v, $cb);
        }

        return htmlentities($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Trim a string to a specified lenght and escape it.
     *
     * @param string      $inputString
     * @param int         $maxLen
     * @param null|string $cb
     *
     * @throws \RangeException
     *
     * @return string
     */
    private function etr($inputString, $maxLen, $cb = null)
    {
        if ($maxLen < 3) {
            throw new RangeException('"maxLen" must be >= 3');
        }

        $strLen = mb_strlen($inputString);
        if ($strLen <= $maxLen) {
            return $inputString;
        }

        $partOne = mb_substr($inputString, 0, (int) ceil(($maxLen - 1) / 2));
        $partTwo = mb_substr($inputString, (int) -floor(($maxLen - 1) / 2));

        return $this->e($partOne.'…'.$partTwo, $cb);
    }

    /**
     * @param string $v
     * @param string $cb
     *
     * @return string
     */
    private function batch($v, $cb)
    {
        $functionList = explode('|', $cb);
        foreach ($functionList as $f) {
            if ('escape' === $f) {
                $v = $this->e($v);

                continue;
            }
            if (array_key_exists($f, $this->callbackList)) {
                $f = $this->callbackList[$f];
            } else {
                if (!function_exists($f)) {
                    throw new TplException(sprintf('function "%s" does not exist', $f));
                }
            }
            $v = call_user_func($f, $v);
        }

        return $v;
    }

    /**
     * Format a date.
     *
     * @param string $dateString
     * @param string $dateFormat
     *
     * @return string
     */
    private function d($dateString, $dateFormat = 'Y-m-d H:i:s')
    {
        $dateTime = new DateTime($dateString);
        $dateTime->setTimeZone(new DateTimeZone(date_default_timezone_get()));

        return $this->e(date_format($dateTime, $dateFormat));
    }

    /**
     * @param string $v
     *
     * @return string
     */
    private function t($v)
    {
        // use original, unless it is found in any of the translation files...
        $translatedText = $v;
        foreach ($this->translationDataList as $translationData) {
            if (array_key_exists($v, $translationData)) {
                // translation found, run with it, we don't care if we find
                // it in other file(s) as well!
                $translatedText = $translationData[$v];

                break;
            }
        }

        // find all string values, wrap the key, and escape the variable
        $escapedVars = [];
        foreach ($this->templateVariables as $k => $v) {
            if (is_string($v)) {
                $escapedVars['%'.$k.'%'] = $this->e($v);
            }
        }

        return str_replace(array_keys($escapedVars), array_values($escapedVars), $translatedText);
    }

    /**
     * @param string $templateName
     *
     * @return bool
     */
    private function exists($templateName)
    {
        foreach ($this->templateFolderList as $templateFolder) {
            $templatePath = sprintf('%s/%s.php', $templateFolder, $templateName);
            if (file_exists($templatePath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $templateName
     *
     * @return string
     */
    private function templatePath($templateName)
    {
        foreach (array_reverse($this->templateFolderList) as $templateFolder) {
            $templatePath = sprintf('%s/%s.php', $templateFolder, $templateName);
            if (file_exists($templatePath)) {
                return $templatePath;
            }
        }

        throw new TplException(sprintf('template "%s" does not exist', $templateName));
    }
}
