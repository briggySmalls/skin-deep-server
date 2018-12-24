<?php

namespace SkinDeep\Widgets;

use SkinDeep\Articles\Article;
use SkinDeep\Common\Post;

class WidgetArgsHelper implements ArgsHelperInterface
{
    protected $args;

    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Helper function to get an ACF field for the given widget
     */
    public function getAcfField($field)
    {
        return get_field($field, 'widget_' . $this->args['widget_id']);
    }

    /**
     * @brief      Wraps an array of posts as an array of Article objects
     * @param      $posts  The posts
     * @return     An array of Article objects
     */
    public static function toArticles($posts)
    {
        return array_map(
            function ($post) {
                return new Article($post);
            },
            $posts
        );
    }

    /**
     * @brief      Wraps an array of posts as an array of Post objects
     * @param      $posts  The posts
     * @return     An array of Post objects
     */
    public static function toPosts($posts)
    {
        return array_map(
            function ($post) {
                return new Post($post);
            },
            $posts
        );
    }
}
