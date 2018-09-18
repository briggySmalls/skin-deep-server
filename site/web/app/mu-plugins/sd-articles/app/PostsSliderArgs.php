<?php

namespace SkinDeep\Articles;

class PostsSliderArgs extends WidgetArgs
{
    public $args = null;
    public $posts = null;

    public function __construct($args)
    {
        $this->args = $args;
        $this->posts = $this->toArticles(
            $this->getAcfField('sd_widget_slider_articles'));
    }
}
