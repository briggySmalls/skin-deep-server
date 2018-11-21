<?php

namespace SkinDeep\Articles;

class PostsSliderArgs implements WidgetArgsInterface
{
    public $posts;

    public function __construct($posts)
    {
        $this->posts = $posts;
    }

    public static function fromArgs($args_helper)
    {
        return new PostsSliderArgs(
            WidgetArgsHelper::toArticles(
                $args_helper->getAcfField('sd_widget_slider_articles')
            )
        );
    }
}
