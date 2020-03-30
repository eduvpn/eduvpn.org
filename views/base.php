<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Generated on <?php echo $this->e($generatedOn); ?> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->e($blogTitle); ?> - <?php echo $this->e($pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->e($requestRoot); ?>css/bootstrap-reboot.css?mtime=<?php echo $unixTime; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->e($requestRoot); ?>css/screen.css?mtime=<?php echo $unixTime; ?>">
</head>
<body>
    <header>
    </header>
    <div class="page">
        <nav>
            <ul>
<?php foreach ($pagesList as $p): ?>
<?php if (!$p['hidePage']): ?>
<?php if ($p['fileName'] === $activePage): ?>
                <li class="active">
                    <a href="<?php echo $this->e($requestRoot); ?><?php echo $this->e($p['fileName']); ?>"><?php echo $this->e($p['title']); ?></a>
                </li>
<?php else: ?>
                <li>
                    <a href="<?php echo $this->e($requestRoot); ?><?php echo $this->e($p['fileName']); ?>"><?php echo $this->e($p['title']); ?></a>
                </li>
<?php endif; ?>
<?php endif; ?>
    <?php endforeach; ?>
            </ul>
        </nav>
        <main>
            <?php echo $this->section('content'); ?>
        </main>
        <aside>
            <?php echo $this->insert('aside', ['latestBlog' => $latestBlog]); ?>
        </aside>
    </div> <!-- /page -->
    <footer>
        <p>&copy; <?php echo $this->e($currentYear); ?> <?php echo $this->e($blogAuthor); ?></p>
    </footer>
</body>
</html>
