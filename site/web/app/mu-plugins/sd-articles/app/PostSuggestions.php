<?php

namespace SkinDeep\Articles;

/**
 * Post suggestions widget
 * Displays methods to navigate to further posts related to the current
 */
class PostSuggestions extends Widget
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
    protected const WIDGET_SLUG = 'suggestions';

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
            __('Post suggestions', self::WIDGET_SLUG),
            __('Suggest how to navigate to relevant posts.', self::WIDGET_SLUG),
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
        return new PostsSuggestionsArgs($args);
    }

    protected function widgetSlug()
    {
        return self::WIDGET_SLUG;
    }
} // end class
