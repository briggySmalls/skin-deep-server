<?php

namespace SkinDeep\Widgets\PostsSlider;

use SkinDeep\Widgets\Widget;

/**
 * Posts slider widget
 * Displays selection of posts in a featured slider
 */
class PostsSlider extends Widget
{

    /**
     * Unique identifier for the widget.
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * widget file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected const WIDGET_SLUG = 'slider';

    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct()
    {
        parent::__construct(
            __('Posts Slider', self::WIDGET_SLUG),
            __('Preview of posts in a featured slider.', self::WIDGET_SLUG)
        );
    }

    /*--------------------------------------------------*/
    /* Protected Functions
    /*--------------------------------------------------*/

    /**
     * @brief      Factory method for creating args for the widget
     * @param      $args  The arguments
     * @return     { description_of_the_return_value }
     */
    protected function createArgs($args_helper)
    {
        return PostsSliderArgs::fromArgs($args_helper);
    }
} // end class
