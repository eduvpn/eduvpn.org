<?php

namespace eduVPN\Web;

use Michelf\MarkdownExtra;

class Markdown
{
    /** @var \Michelf\MarkdownExtra */
    private $md;

    /** @var array */
    private $headerList = [];

    public function __construct()
    {
        $this->md = new MarkdownExtra();
        $this->md->url_filter_func = function ($inputUri) {
            return $this->urlFilterFunction($inputUri);
        };
        $this->md->header_id_func = function ($header) {
            return $this->headerIdFunction($header);
        };
    }

    /**
     * @param string $buffer
     *
     * @return string
     */
    public function transform($buffer)
    {
        $this->headerList = [];

        return $this->md->transform($buffer);
    }

    /**
     * @param string $inputUri
     *
     * @return string
     */
    private function urlFilterFunction($inputUri)
    {
        // do not touch absolute URLs
        $uriPrefixList = ['http://', 'https://', 'mailto:'];
        foreach ($uriPrefixList as $uriPrefix) {
            if (0 === stripos($inputUri, $uriPrefix)) {
                return $inputUri;
            }
        }

        // if relative URLs end with ".md", change them to ".html"
        if (0 !== preg_match('/(.*)\.md$/i', $inputUri, $matches)) {
            //echo 'LOCAL: ' . $inputUri . PHP_EOL;
            return strtolower($matches[1]).'.html';
        }
        // if relative URLs end with ".md", but contain an anchor, also change them
        // to ".html" and restore the anchor
        if (0 !== preg_match('/(.*)\.md#(.*)$/', $inputUri, $matches)) {
            //echo 'LOCAL ANCHOR: ' . $inputUri . PHP_EOL;
            return strtolower($matches[1]).'.html#'.$matches[2];
        }

        // local anchor
        if (0 === stripos($inputUri, '#')) {
            return $inputUri;
        }

        echo 'Probably a "dead" link: '.$inputUri.PHP_EOL;

        return $inputUri;
    }

    /**
     * @param string $header
     *
     * @return string
     */
    private function headerIdFunction($header)
    {
        $i = 0;
        $header = preg_replace('/[^a-z0-9]/', '-', strtolower($header));
        while (\in_array($header, $this->headerList, true)) {
            if (0 !== $i) {
                // remove the existing prefix
                $foo = explode('-', $header);
                $foo = \array_slice($foo, 0, \count($foo) - 1);
                $header = implode('-', $foo);
            }
            // add prefix with number
            ++$i;
            $header = $header.'-'.$i;
            $header = preg_replace('/[^a-z0-9]/', '-', strtolower($header));
        }
        $this->headerList[] = $header;

        return $header;
    }
}
