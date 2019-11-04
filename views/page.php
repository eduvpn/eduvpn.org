<?php $this->layout('base'); ?>
<?php $this->start('content'); ?>
    <h1><?php echo $this->e($pageTitle); ?></h1>
    <?php echo $pageContent['htmlContent']; ?>
<?php $this->stop('content'); ?>
