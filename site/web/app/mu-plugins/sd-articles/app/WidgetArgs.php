<?php

namespace SkinDeep\Articles;

abstract class WidgetArgs
{
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
    public function toArticles($posts)
    {
        return array_map(
            function($post) { return new Article($post); },
            $posts);
    }
}
