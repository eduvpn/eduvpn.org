<?php foreach ($postsYearList as $year => $postsList) { ?>
<h3><?php echo $this->e($year); ?></h3>
    <ul class="index">
<?php foreach ($postsList as $post) { ?>
<?php if ($post['publish']) { ?>
        <li>
            <a href="blog/<?php echo $this->e($post['fileName']); ?>"><?php echo $this->e($post['title']); ?></a> <small><?php echo $this->e($post['published']); ?></small>
        </li>
<?php } ?>
<?php } ?>
    </ul>
<?php } ?>
