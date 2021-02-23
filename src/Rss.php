<?php

class Rss
{
    public static function generate(string $title, string $link, string $description, array $itemList)
    {
        ob_start();

        include __DIR__.'/rss_tpl.php';

        return ob_get_clean();
    }
}
