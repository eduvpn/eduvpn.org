<?php $this->layout('base'); ?>
<?php $this->start('content'); ?>
    <h2><?php echo $this->e($pageTitle); ?></h2>
    <p>
        <small>
            Published on <?php echo $this->e($post['published']); ?>
        </small>
    </p>
    <?php echo $post['htmlContent']; ?>

<?php if ($post['modified']) { ?>
        <p class="center"><small>Last Modified on <?php echo $this->e($post['modified']); ?></small></p>
<?php } ?>
        <p class="center"><small><a href="<?php echo $this->e($post['postHistory']); ?>">History</a></small></p>
<?php $this->stop('content'); ?>
