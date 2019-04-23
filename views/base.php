<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Generated on <?php echo $this->e($generatedOn); ?> -->
    <meta name="viewport" content="width=device-width; height=device-height; initial-scale=1">
    <title><?php echo $this->e($blogTitle); ?> - <?php echo $this->e($pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->e($requestRoot); ?>css/bootstrap-reboot.css">    
    <link rel="stylesheet" type="text/css" href="<?php echo $this->e($requestRoot); ?>css/screen.css?timestamp=<?php echo $unixTime; ?>">
</head>
<body>
    <div class="header">
        <h1><?php echo $this->e($blogTitle); ?></h1>
        <h2><?php echo $this->e($blogDescription); ?></h2>
        <ul class="pages">
<?php foreach ($pagesList as $p): ?>
<?php if ($p['fileName'] === $activePage): ?>
            <li class="active">
                <a href="<?php echo $this->e($requestRoot); ?><?php echo $this->e($p['fileName']); ?>"><?php echo $this->e($p['title']); ?></a>
            </li>
<?php else: ?>
            <li>
                <a href="<?php echo $this->e($requestRoot); ?><?php echo $this->e($p['fileName']); ?>"><?php echo $this->e($p['title']); ?></a>
            </li>
<?php endif; ?>
<?php endforeach; ?>
        </ul>
    </div> <!-- /header -->
    <div class="content">
        <?php echo $this->section('content'); ?>
    </div> <!-- /content -->

    <div class="footer">
        <p>&copy; <?php echo $this->e($currentYear); ?> <?php echo $this->e($blogAuthor); ?></p>
    </div> <!-- /footer -->
</body>
</html>
