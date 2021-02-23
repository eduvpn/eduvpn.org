<?xml version="1.0" ?>
<rss version="2.0">
<channel>
    <title><?php echo $title; ?></title>
    <link><?php echo $link; ?></link>
    <description><?php echo $description; ?></description>
<?php foreach ($itemList as $item) { ?>
    <item>
        <title><?php echo htmlentities($item['title']); ?></title>
        <pubDate><?php echo $item['pubDate']->format('D, d M Y H:i:s O'); ?></pubDate>
        <link><?php echo $item['link']; ?></link>
        <description><![CDATA[<?php echo $item['description']; ?>]]></description>
    </item>
<?php } ?>
</channel>
</rss>
