<?php

namespace SkinDeep\Articles;

/**
 * Posts preview widget
 * Displays selection of posts in a taxonomy, format, etc
 */
class PostsPreview extends Widget
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
    protected const WIDGET_SLUG = 'preview';

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
            __('Posts Preview', self::WIDGET_SLUG),
            __('Preview of posts in a configured group.', self::WIDGET_SLUG),
            new ResourceManager(__DIR__),
            TEMPLATE_NAMESPACE
        );
    } // end constructor

    /*--------------------------------------------------*/
    /* Protected Functions
    /*--------------------------------------------------*/

    /**
     * @brief      Factory method for creating args for the widget
     * @param      $args  The arguments
     * @return     { description_of_the_return_value }
     */
    protected function createArgs($args)
    {
        return new PostsPreviewArgs($args);
    }

    protected function widgetSlug()
    {
        return self::WIDGET_SLUG;
    }
} // end class
