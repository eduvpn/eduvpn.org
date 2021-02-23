<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

require_once dirname(__DIR__).'/src/Tpl.php';

require_once dirname(__DIR__).'/src/Rss.php';

$baseDir = dirname(__DIR__);

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Table\TableExtension;

$blogTitle = 'eduVPN';
$blogDescription = ''; //'Safe and Trusted';
$blogAuthor = 'eduVPN';
$blogBaseUrl = 'https://www.eduvpn.org/blog';
$gitRepoWebUrl = 'https://github.com/eduvpn/eduvpn.org/';
$dateTime = new DateTime();
$postDir = sprintf('%s/posts', $baseDir);
$pageDir = sprintf('%s/pages', $baseDir);
$outputDir = sprintf('%s/output', $baseDir);
$blogOutputDir = sprintf('%s/blog', $outputDir);
$templateDir = sprintf('%s/views', $baseDir);

@mkdir($outputDir, 0755, true);
@mkdir($blogOutputDir, 0755, true);
@mkdir($outputDir.'/img', 0755, true);
@mkdir($outputDir.'/files', 0755, true);
@mkdir($outputDir.'/css', 0755, true);

$environment = Environment::createCommonMarkEnvironment();
$environment->addExtension(new SmartPunctExtension());
$environment->addExtension(new TableExtension());
$environment->addExtension(new HeadingPermalinkExtension());
$converter = new CommonMarkConverter([], $environment);
$templates = new Tpl([$templateDir]);
$templates->addDefault(
    [
        'blogTitle' => $blogTitle,
        'blogDescription' => $blogDescription,
        'blogAuthor' => $blogAuthor,
        'generatedOn' => $dateTime->format(DateTime::ATOM),
        'currentYear' => $dateTime->format('Y'),
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
        'postHistory' => $gitRepoWebUrl.'log/main/item/'.basename($postDir).'/'.basename($postFile),
    ];

    $postsList[] = $blogPost;
}

usort($postsList, function ($a, $b) {
    return strtotime($a['published']) < strtotime($b['published']);
});

$postsYearList = [];
foreach ($postsList as $postInfo) {
    if (!$postInfo['publish']) {
        continue;
    }
    $postDate = new DateTime($postInfo['published']);
    $postYear = $postDate->format('Y');
    if (!array_key_exists($postYear, $postsYearList)) {
        $postsYearList[$postYear] = [];
    }
    $postsYearList[$postYear][] = $postInfo;
}

// add blog index page
array_unshift(
    $pagesList,
    [
        'htmlContent' => $templates->render(
            'index',
            [
                'postsYearList' => $postsYearList,
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

$rssItemList = [];
foreach ($postsList as $post) {
    if ($post['publish']) {
        $rssItemList[] = [
            'title' => $post['title'],
            'link' => $blogBaseUrl.'/blog/'.$post['fileName'],
            'pubDate' => new DateTime($post['published']),
            'description' => trim($post['htmlContent']),
        ];
    }
}

// generate RSS
file_put_contents(
    $blogOutputDir.'/feed.xml',
    Rss::generate(
        $blogTitle,
        $blogBaseUrl,
        $blogDescription,
        $rssItemList
    )
);

foreach ($pagesList as $page) {
    $pagePage = $templates->render(
        'page',
        [
            'requestRoot' => '',
            'activePage' => $page['fileName'],
            'pagesList' => $pagesList,
            'pageTitle' => $page['title'],
            'pageContent' => $page,
        ]
    );
    file_put_contents($outputDir.'/'.$page['fileName'], $pagePage);
}

foreach (['img', 'files', 'css'] as $pathName) {
    foreach (glob($baseDir.'/'.$pathName.'/*') as $file) {
        copy($file, $outputDir.'/'.$pathName.'/'.basename($file));
    }
}
