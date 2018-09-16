<?php

namespace SkinDeep\Articles;

class Articles
{
    public static $blade;

    public static function is_default_category($category)
    {
        $default_category = get_option('default_category');
        return $default_category == $category->term_id;
    }
}
