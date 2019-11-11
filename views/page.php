<?php $this->layout('base'); ?>
<?php $this->start('content'); ?>
    <h2><?php echo $this->e($pageTitle); ?></h2>
<?php if (false !== $latestBlog): ?>
<p class="latest">
Read our Latest Blog Post <a href="blog/<?php echo $this->e($latestBlog['fileName']); ?>"><?php echo $this->e($latestBlog['title']); ?></a>
</p>
<?php endif; ?>
    <?php echo $pageContent['htmlContent']; ?>
<?php $this->stop('content'); ?>
