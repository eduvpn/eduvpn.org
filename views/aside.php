<?php if (false !== $latestBlog): ?>
<p class="latest">
Our Latest Blog Post <a href="blog/<?php echo $this->e($latestBlog['fileName']); ?>"><?php echo $this->e($latestBlog['title']); ?></a>
</p>
<?php endif; ?>
