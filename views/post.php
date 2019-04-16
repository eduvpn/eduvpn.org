<?php $this->layout('base'); ?>
<?php $this->start('content'); ?>
    <p class="date">
        <small>Published: <?php echo $this->e($post['published']); ?></small>
    </p>
<?php if ($post['modified']): ?>
    <p>
        <small>Updated: <?php echo $this->e($post['modified']); ?></small>
    </p>
<?php endif; ?>
    
    <?php echo $post['htmlContent']; ?>

    <p class="center"><small><a href="<?php echo $this->e($requestRoot); ?>index.html">Index</a></small></p>
<?php $this->stop(); ?>
