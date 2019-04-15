<?php

require_once dirname(__DIR__).'/vendor/autoload.php';
$baseDir = dirname(__DIR__);

use League\CommonMark\CommonMarkConverter;

$postDir = sprintf('%s/posts', $baseDir);
$pageDir = sprintf('%s/pages', $baseDir);
$outputDir = sprintf('%s/output', $baseDir);
$blogOutputDir = sprintf('%s/blog', $outputDir);
$templateDir = sprintf('%s/views', $baseDir);

$blogTitle = 'eduVPN';
$blogDescription = 'Safe and Trusted';
$blogAuthor = 'eduVPN';

$loader = new Twig_Loader_Filesystem($templateDir);
$twig = new Twig_Environment($loader, ['strict_variables' => true]);

$dateTime = new DateTime();

$postsList = [];
$pagesList = [];

@mkdir($outputDir, 0755, true);
@mkdir($blogOutputDir, 0755, true);

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

    $converter = new CommonMarkConverter();
    $page = [
        'htmlContent' => $converter->convertToHtml($buffer),
        'publish' => isset($pageInfo['publish']) ? 'no' !== $pageInfo['publish'] : true,
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

    $converter = new CommonMarkConverter();

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

$siteIndexPage = $twig->render(
    'index.twig',
    [
        'unixTime' => time(),
        'toRoot' => '',
        'pagesList' => $pagesList,
        'activePage' => 'index.html',
        'postsList' => $postsList,
        'pageTitle' => 'Blog',
        'blogTitle' => $blogTitle,
        'blogDescription' => $blogDescription,
        'blogAuthor' => $blogAuthor,
    ]
);

$blogIndexPage = $twig->render(
    'index.twig',
    [
        'unixTime' => time(),
        'toRoot' => '../',
        'pagesList' => $pagesList,
        'activePage' => 'index.html',
        'postsList' => $postsList,
        'pageTitle' => 'Blog',
        'blogTitle' => $blogTitle,
        'blogDescription' => $blogDescription,
        'blogAuthor' => $blogAuthor,
    ]
);

foreach ($postsList as $post) {
    if ($post['publish']) {
        $postPage = $twig->render(
            'post.twig',
            [
                'unixTime' => time(),
                'toRoot' => '../',
                'pagesList' => $pagesList,
                'activePage' => 'index.html',
                'blogTitle' => $blogTitle,
                'pageTitle' => $post['title'],
                'post' => $post,
                'blogDescription' => $blogDescription,
                'blogAuthor' => $blogAuthor,
            ]
        );
        file_put_contents($blogOutputDir.'/'.$post['fileName'], $postPage);
    }
}

foreach ($pagesList as $page) {
    if ($post['publish']) {
        $pagePage = $twig->render(
            'page.twig',
            [
                'unixTime' => time(),
                'toRoot' => '',
                'activePage' => $page['fileName'],
                'pagesList' => $pagesList,
                'blogTitle' => $blogTitle,
                'pageTitle' => $page['title'],
                'pageContent' => $page,
                'blogDescription' => $blogDescription,
                'blogAuthor' => $blogAuthor,
            ]
        );
        file_put_contents($outputDir.'/'.$page['fileName'], $pagePage);
    }
}

file_put_contents($outputDir.'/index.html', $siteIndexPage);
file_put_contents($blogOutputDir.'/index.html', $blogIndexPage);

// copy img
@mkdir($outputDir.'/img');
foreach (glob($baseDir.'/img/*') as $imgFile) {
    copy($imgFile, $outputDir.'/img/'.basename($imgFile));
}

// copy download
@mkdir($outputDir.'/download');
foreach (glob($baseDir.'/download/*') as $imgFile) {
    copy($imgFile, $outputDir.'/download/'.basename($imgFile));
}

// copy css
@mkdir($outputDir.'/css');
foreach (glob($baseDir.'/css/*') as $cssFile) {
    copy($cssFile, $outputDir.'/css/'.basename($cssFile));
}
