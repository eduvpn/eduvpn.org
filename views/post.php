<?php $this->layout('base'); ?>
<?php $this->start('content'); ?>
    <h1><?php echo $this->e($pageTitle); ?></h1>
    <p>
        <small><?php echo $this->e($post['published']); ?></small>
    </p>

    <?php echo $post['htmlContent']; ?>

<?php if ($post['modified']): ?>
    <p class="center">
        <small>
            Updated on <?php echo $this->e($post['modified']); ?>
        </small>
    </p>
<?php endif; ?>
    <p class="center">    <small><a href="index.html">Blog Index</a></small>
</p>
<?php $this->stop('content'); ?>
