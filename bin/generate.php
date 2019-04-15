<?php

require_once dirname(__DIR__).'/vendor/autoload.php';
$baseDir = dirname(__DIR__);

use fkooman\Tpl\Template;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Ext\SmartPunct\SmartPunctExtension;

$postDir = sprintf('%s/posts', $baseDir);
$pageDir = sprintf('%s/pages', $baseDir);
$outputDir = sprintf('%s/output', $baseDir);
$blogOutputDir = sprintf('%s/blog', $outputDir);
$templateDir = sprintf('%s/views', $baseDir);

@mkdir($outputDir, 0755, true);
@mkdir($blogOutputDir, 0755, true);
@mkdir($outputDir.'/img', 0755, true);
@mkdir($outputDir.'/download', 0755, true);
@mkdir($outputDir.'/css', 0755, true);

// Obtain a pre-configured Environment with all the CommonMark parsers/renderers ready-to-go
$environment = Environment::createCommonMarkEnvironment();

// Add this extension
$environment->addExtension(new SmartPunctExtension());

// Instantiate the converter engine and start converting some Markdown!
$converter = new CommonMarkConverter([], $environment);

$templates = new Template([$templateDir]);
$templates->addDefault(
    [
        'blogTitle' => 'eduVPN',
        'blogDescription' => 'Safe and Trusted',
        'blogAuthor' => 'eduVPN',
        'unixTime' => time(),
    ]
);

$postsList = [];
$pagesList = [];

foreach (glob(sprintf('%s/*.md', $pageDir)) as $pageFile) {
    $pageInfo = [];

    $f = fopen($pageFile, 'r');
    $line = fgets($f);
    if (0 !== strpos($line, '---')) {
        throw new Exception(sprintf('invalid file "%s"!', $pageFile));
    }
    $line = fgets($f);
    do {
        $xx = explode(':', $line, 2);
        $pageInfo[trim($xx[0])] = trim($xx[1]);
        $line = fgets($f);
    } while (0 !== strpos($line, '---'));

    // read rest of the page
    $buffer = '';
    while (!feof($f)) {
        $buffer .= fgets($f);
    }

    fclose($f);
    $pageOutputFile = basename($pageFile, '.md').'.html';

    $page = [
        'htmlContent' => $converter->convertToHtml($buffer),
        'title' => $pageInfo['title'],
        'fileName' => $pageOutputFile,
    ];
    $pagesList[] = $page;
}

foreach (glob(sprintf('%s/*.md', $postDir)) as $postFile) {
    $postInfo = [];

    // obtain postInfo
    $f = fopen($postFile, 'r');
    $line = fgets($f);
    if (0 !== strpos($line, '---')) {
        throw new Exception(sprintf('invalid file! "%s"', $postFile));
    }
    $line = fgets($f);
    do {
        $xx = explode(':', $line, 2);
        $postInfo[trim($xx[0])] = trim($xx[1]);
        $line = fgets($f);
    } while (0 !== strpos($line, '---'));

    // read rest of the post
    $buffer = '';
    while (!feof($f)) {
        $buffer .= fgets($f);
    }

    fclose($f);
    $postOutputFile = basename($postFile, '.md').'.html';
    $blogPost = [
        'htmlContent' => $converter->convertToHtml($buffer),
        'description' => isset($postInfo['description']) ? $postInfo['description'] : $postInfo['title'],
        'published' => $postInfo['published'],
        'publish' => isset($postInfo['publish']) ? 'no' !== $postInfo['publish'] : true,
        'title' => $postInfo['title'],
        'modified' => isset($postInfo['modified']) ? $postInfo['modified'] : null,
        'fileName' => $postOutputFile,
    ];
    $postsList[] = $blogPost;
}

usort($postsList, function ($a, $b) {
    return strtotime($a['published']) < strtotime($b['published']);
});

// add blog index page
array_unshift(
    $pagesList,
    [
        'htmlContent' => $templates->render(
            'index',
            [
                'postsList' => $postsList,
            ]
        ),
        'title' => 'Blog',
        'fileName' => 'index.html',
    ]
);

foreach ($postsList as $post) {
    if ($post['publish']) {
        $postPage = $templates->render(
            'post',
            [
                'unixTime' => time(),
                'requestRoot' => '../',
                'pagesList' => $pagesList,
                'activePage' => 'index.html',
                'pageTitle' => $post['title'],
                'post' => $post,
            ]
        );
        file_put_contents($blogOutputDir.'/'.$post['fileName'], $postPage);
    }
}

foreach ($pagesList as $page) {
    $pagePage = $templates->render(
        'page',
        [
            'unixTime' => time(),
            'requestRoot' => '',
            'activePage' => $page['fileName'],
            'pagesList' => $pagesList,
            'pageTitle' => $page['title'],
            'pageContent' => $page,
        ]
    );
    file_put_contents($outputDir.'/'.$page['fileName'], $pagePage);
}

//file_put_contents($outputDir.'/index.html', $siteIndexPage);

// copy img
foreach (glob($baseDir.'/img/*') as $imgFile) {
    copy($imgFile, $outputDir.'/img/'.basename($imgFile));
}

// copy download
foreach (glob($baseDir.'/download/*') as $imgFile) {
    copy($imgFile, $outputDir.'/download/'.basename($imgFile));
}

// copy css
foreach (glob($baseDir.'/css/*') as $cssFile) {
    copy($cssFile, $outputDir.'/css/'.basename($cssFile));
}
