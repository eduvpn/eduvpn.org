<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Generated on <?php echo $this->e($generatedOn); ?> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->e($blogTitle); ?> - <?php echo $this->e($pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->e($requestRoot); ?>css/simple.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->e($requestRoot); ?>css/screen.css">
</head>
<body>
<header>
        <h1><?php echo $this->e($blogTitle); ?></h1>
<?php if ('' !== $blogDescription) { ?>
        <p><?php echo $this->e($blogDescription); ?></p>
<?php } ?>
<nav>
<?php foreach ($pagesList as $p) { ?>
<?php if ($p['fileName'] === $activePage) { ?>
                <a href="<?php echo $this->e($requestRoot); ?><?php echo $this->e($p['fileName']); ?>"><?php echo $this->e($p['title']); ?></a>
<?php } else { ?>
                <a href="<?php echo $this->e($requestRoot); ?><?php echo $this->e($p['fileName']); ?>"><?php echo $this->e($p['title']); ?></a>
<?php } ?>
<?php } ?>
</nav>
</header>
<main>
        <?php echo $this->section('content'); ?>
</main>

<footer>
        <p>&copy; <?php echo $this->e($currentYear); ?> <?php echo $this->e($blogAuthor); ?></p>
</footer>
</body>
</html>
