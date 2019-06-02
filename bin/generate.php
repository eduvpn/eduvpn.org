<?php

require_once dirname(__DIR__).'/vendor/autoload.php';
$baseDir = dirname(__DIR__);

use eduVPN\Web\Markdown;
use fkooman\Tpl\Template;

$dateTime = new DateTime();
$postDir = sprintf('%s/posts', $baseDir);
$pageDir = sprintf('%s/pages', $baseDir);
$docsDir = sprintf('%s/docs', $baseDir);
$outputDir = sprintf('%s/output', $baseDir);
$blogOutputDir = sprintf('%s/blog', $outputDir);
$docOutputDir = sprintf('%s/docs', $outputDir);
$templateDir = sprintf('%s/views', $baseDir);

@mkdir($outputDir, 0755, true);
@mkdir($blogOutputDir, 0755, true);
@mkdir($docOutputDir, 0755, true);
@mkdir($outputDir.'/img', 0755, true);
@mkdir($outputDir.'/download', 0755, true);
@mkdir($outputDir.'/css', 0755, true);

$md = new Markdown();

$templates = new Template([$templateDir]);
$templates->addDefault(
    [
        'blogTitle' => 'eduVPN',
        'blogDescription' => 'Safe and Trusted',
        'blogAuthor' => 'eduVPN',
        'generatedOn' => $dateTime->format(DateTime::ATOM),
        'currentYear' => $dateTime->format('Y'),
    ]
);

$postsList = [];
$pagesList = [];
$docsList = [];

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
        'htmlContent' => $md->transform($buffer),
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

$postsYearList = [];
foreach ($postsList as $postInfo) {
    $postDate = new DateTime($postInfo['published']);
    $postYear = $postDate->format('Y');
    if (!array_key_exists($postYear, $postsYearList)) {
        $postsYearList[$postYear] = [];
    }
    $postsYearList[$postYear][] = $postInfo;
}

foreach (glob(sprintf('%s/*', $docsDir)) as $docVer) {
    $docVer = basename($docVer);
    $docsList[$docVer] = [];
    foreach (glob(sprintf('%s/%s/*.md', $docsDir, $docVer)) as $docFile) {
        $docInfo = [];

        // obtain docInfo
        $f = fopen($docFile, 'r');
        $line = fgets($f);
        if (0 !== strpos($line, '---')) {
            throw new Exception(sprintf('invalid file! "%s"', $docFile));
        }
        $line = fgets($f);
        do {
            $xx = explode(':', $line, 2);
            $docInfo[trim($xx[0])] = trim($xx[1]);
            $line = fgets($f);
        } while (0 !== strpos($line, '---'));

        // read rest of the doc
        $buffer = '';
        while (!feof($f)) {
            $buffer .= fgets($f);
        }

        fclose($f);
        $docOutputFile = basename($docFile, '.md').'.html';
        $docPage = [
            'htmlContent' => $md->transform($buffer),
            'description' => isset($docInfo['description']) ? $docInfo['description'] : $docInfo['title'],
            'title' => $docInfo['title'],
            'modified' => isset($docInfo['modified']) ? $docInfo['modified'] : null,
            'fileName' => $docOutputFile,
        ];

        $docsList[$docVer][] = $docPage;
    }
}

//var_dump($docsList);
//die();

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
        'htmlContent' => $md->transform($buffer),
        'title' => $pageInfo['title'],
        'fileName' => $pageOutputFile,
        'priority' => isset($pageInfo['priority']) ? (int) $pageInfo['priority'] : 255,
        'latestBlog' => isset($pageInfo['latest_blog']) ? $postsList[0] : false,
        'hidePage' => isset($pageInfo['hide-page']) ? 'yes' === $pageInfo['hide-page'] : false,
    ];
    $pagesList[] = $page;
}

// add blog page
$pagesList[] = [
    'htmlContent' => $templates->render(
        'index',
        [
            'postsYearList' => $postsYearList,
        ]
    ),
    'hidePage' => false,
    'title' => 'Blog',
    'requestRoot' => '../',
    'fileName' => 'blog/index.html',
    'priority' => 255,
    'latestBlog' => false,
];

// add docs page
$pagesList[] = [
    'htmlContent' => $templates->render(
        'docs_index',
        [
            'doc_version_list' => array_keys($docsList),
        ]
    ),
    'hidePage' => false,
    'title' => 'Documentation',
    'requestRoot' => '../',
    'fileName' => 'docs/index.html',
    'priority' => 255,
    'latestBlog' => false,
];

// sort pages by priority
usort($pagesList, function ($a, $b) {
    if ($a['priority'] === $b['priority']) {
        return 0;
    }

    return ($a['priority'] < $b['priority']) ? -1 : 1;
});

foreach ($postsList as $post) {
    if ($post['publish']) {
        $postPage = $templates->render(
            'post',
            [
                'requestRoot' => '../',
                'pagesList' => $pagesList,
                'activePage' => 'blog/index.html',
                'pageTitle' => $post['title'],
                'post' => $post,
            ]
        );
        file_put_contents($blogOutputDir.'/'.$post['fileName'], $postPage);
    }
}

foreach ($docsList as $docVer => $docList) {
    @mkdir($docOutputDir.'/'.$docVer, 0755, true);
    foreach ($docList as $doc) {
        $docPage = $templates->render(
            'doc',
            [
                'requestRoot' => '../../',
                'pagesList' => $pagesList,
                'activePage' => 'docs/index.html',
                'pageTitle' => $doc['title'],
                'doc' => $doc,
            ]
        );
        file_put_contents($docOutputDir.'/'.$docVer.'/'.$doc['fileName'], $docPage);
    }
}

foreach ($pagesList as $page) {
    $pagePage = $templates->render(
        'page',
        [
            'requestRoot' => isset($page['requestRoot']) ? $page['requestRoot'] : '',
            'activePage' => $page['fileName'],
            'pagesList' => $pagesList,
            'pageTitle' => $page['title'],
            'pageContent' => $page,
            'latestBlog' => $page['latestBlog'],
        ]
    );
    file_put_contents($outputDir.'/'.$page['fileName'], $pagePage);
}

foreach (['img', 'download', 'css'] as $pathName) {
    foreach (glob($baseDir.'/'.$pathName.'/*') as $file) {
        copy($file, $outputDir.'/'.$pathName.'/'.basename($file));
    }
}
