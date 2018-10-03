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

    /**
     * Namespace in which blade templates are identified
     */
    protected const TEMPLATE_NAMESPACE = 'articles';

    /**
     * Bootstrap columns for responsive breakpoints
     */
    public const BOOTSTRAP_COLUMNS = [
        'xs' => 0, // Extra small screen / phone
        'sm' => 576, // Small screen / phone
        'md' => 768, // Medium screen / tablet
        'lg' => 992, // Large screen / desktop
        'xl' => 1200,   // Extra large screen / wide desktop
    ];

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
            new ResourceManager(__DIR__)
        );
    }

    /*--------------------------------------------------*/
    /* Public Functions
    /*--------------------------------------------------*/

    public static function sizes($column_count)
    {
        $approx_width = round(100 / $column_count);

        return sprintf(
            '(max-width: %upx) %uvw, (max-width: %upx) %uvw, %upx',
            self::BOOTSTRAP_COLUMNS['md'],
            100,
            self::BOOTSTRAP_COLUMNS['xl'],
            $approx_width,
            self::BOOTSTRAP_COLUMNS['xl'] / $column_count
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
    protected function createArgs($args)
    {
        return PostsPreviewArgs::fromArgs($args);
    }
} // end class
