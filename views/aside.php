<?php if (false !== $latestBlog): ?>
<p class="latest">
    <strong>New on our blog</strong>: <a href="blog/<?php echo $this->e($latestBlog['fileName']); ?>"><?php echo $this->e($latestBlog['title']); ?></a>
</p>
<!--
<p class="lc">
Not in the Research &amp; Education Community? Try <a href="https://www.letsconnect-vpn.org/">Let's Connect!</a>
</p> -->
<?php endif; ?>
