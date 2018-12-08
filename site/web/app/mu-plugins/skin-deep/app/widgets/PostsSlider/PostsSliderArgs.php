<?php

namespace SkinDeep\Widgets\PostsSlider;

use SkinDeep\Widgets\WidgetArgsInterface;
use SkinDeep\Widgets\WidgetArgsHelper;

class PostsSliderArgs implements WidgetArgsInterface
{
    /**
     * Posts to display in the slider
     */
    public $posts;

    /**
     * A unique ID to give the slider element
     */
    public $id;

    public function __construct($posts)
    {
        $this->posts = $posts;
        $this->id = uniqid('slider-');
    }

    public static function fromArgs($args_helper)
    {
        return new PostsSliderArgs(
            WidgetArgsHelper::toPosts(
                $args_helper->getAcfField('sd_widget_slider_articles')
            )
        );
    }
}
