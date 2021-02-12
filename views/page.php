<?php $this->layout('base'); ?>
<?php $this->start('content'); ?>
    <h2><?php echo $this->e($pageTitle); ?></h2>
    <?php echo $pageContent['htmlContent']; ?>
<?php $this->stop('content'); ?>
