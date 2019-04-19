<?php $this->layout('base'); ?>
<?php $this->start('content'); ?>
    <h2><?php echo $this->e($pageTitle); ?></h2>
    <?php echo $post['htmlContent']; ?>

    <p class="center">
        <small>
            Published on <?php echo $this->e($post['published']); ?>
<?php if ($post['modified']): ?>
            (Updated on <?php echo $this->e($post['modified']); ?>)
<?php endif; ?>
        </small>
    </p>
<?php $this->stop(); ?>
