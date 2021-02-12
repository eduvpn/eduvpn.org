<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Generated on <?php echo $this->e($generatedOn); ?> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->e($blogTitle); ?> - <?php echo $this->e($pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->e($requestRoot); ?>css/simple.css?mtime=<?php echo $unixTime; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->e($requestRoot); ?>css/screen.css?mtime=<?php echo $unixTime; ?>">
</head>
<body>
    <header>
    <h1>eduVPN</h1>
    <nav>
<?php foreach ($pagesList as $p): ?>
<?php if (!$p['hidePage']): ?>
<?php if ($p['fileName'] === $activePage): ?>
                    <a href="<?php echo $this->e($requestRoot); ?><?php echo $this->e($p['fileName']); ?>"><?php echo $this->e($p['title']); ?></a>
<?php else: ?>
                    <a href="<?php echo $this->e($requestRoot); ?><?php echo $this->e($p['fileName']); ?>"><?php echo $this->e($p['title']); ?></a>
<?php endif; ?>
<?php endif; ?>
    <?php endforeach; ?>
        </nav>
    </header>
    <main>
        <section>
            <?php echo $this->insert('aside', ['latestBlog' => $latestBlog]); ?>
        </section>
        <?php echo $this->section('content'); ?>
    </main>
    <footer>
        <p>&copy; <?php echo $this->e($currentYear); ?> <?php echo $this->e($blogAuthor); ?></p>
    </footer>
</body>
</html>
