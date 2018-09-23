<?php

namespace SkinDeep\Articles;

/**
 * Featured posts slider widget
 *
 */
abstract class Widget extends \WP_Widget
{
    protected $resource_manager;
    protected const TEMPLATE_NAMESPACE = null;

    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct($title, $description, $resource_manager)
    {
        $this->resource_manager = $resource_manager;

        // load plugin text domain
        add_action('init', array( $this, 'widgetTextdomain' ));

        parent::__construct(
            static::WIDGET_SLUG,
            $title,
            [
                'classname'  => static::WIDGET_SLUG,
                'description' => $description
            ]
        );

        // Refreshing the widget's cached output with each new post
        add_action('save_post', [ $this, 'flushWidgetCache' ]);
        add_action('deleted_post', [ $this, 'flushWidgetCache' ]);
        add_action('switch_theme', [ $this, 'flushWidgetCache' ]);
        // Register assets (but do not enqueue them)
        add_action('wp_enqueue_scripts', [ $this, 'registerWidgetAssets' ]);
        add_action('admin_enqueue_scripts', [ $this, 'registerAdminAssets' ]);
        // Hooks fired when the Widget is activated and deactivated
        register_activation_hook(__FILE__, [ __NAMESPACE__ . '\\' . get_class(), 'activate' ]);
        register_deactivation_hook(__FILE__, [ __NAMESPACE__ . '\\' . get_class(), 'deactivate' ]);
    } // end constructor

    /*--------------------------------------------------*/
    /* Widget API Functions
    /*--------------------------------------------------*/

    /**
     * Outputs the content of the widget.
     *
     * @param array args  The array of form elements
     * @param array instance The current instance of the widget
     */
    public function widget($args, $instance)
    {
        // Check if there is a cached output
        $cache = wp_cache_get(static::WIDGET_SLUG, 'widget');

        if (!is_array($cache)) {
            $cache = array();
        }

        if (! isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        if (isset($cache[ $args['widget_id'] ])) {
            return print $cache[ $args['widget_id'] ];
        }

        // Enqueue widget styles and scripts now we are going to use them
        $this->enqueueAsset('widget', $is_script = true);
        $this->enqueueAsset('widget', $is_script = false);

        // go on with your widget logic, put everything into a string and …
        extract($args, EXTR_SKIP);
        $widget_string = $before_widget;

        // Generate the widget content from the Blade template
        $context = get_object_vars($this->createArgs($args));
        $widget_string .= self::output('widget', $context);
        $widget_string .= $after_widget;

        $cache[ $args['widget_id'] ] = $widget_string;

        wp_cache_set(static::WIDGET_SLUG, $cache, 'widget');

        print $widget_string;
    } // end widget

    public function flushWidgetCache()
    {
        wp_cache_delete(static::WIDGET_SLUG, 'widget');
    }

    /**
     * Processes the widget's options to be saved.
     *
     * @param array new_instance The new instance of values to be generated via the update.
     * @param array old_instance The previous instance of values before the update.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        // TODO: Here is where you update your widget's old values with the new, incoming values

        return $instance;
    } // end update

    /**
     * Generates the administration form for the widget.
     *
     * @param array instance The array of keys and values for the widget.
     */
    public function form($instance)
    {
        // Enqueue admin styles and scripts now we are going to use them
        $this->enqueueAsset('admin', $is_script = true);
        $this->enqueueAsset('admin', $is_script = false);

        // Display the admin form
        echo self::output('admin', null);
    } // end form

    /*--------------------------------------------------*/
    /* Public Functions
    /*--------------------------------------------------*/

    /**
     * Loads the Widget's text domain for localization and translation.
     */
    public function widgetTextdomain()
    {
        load_plugin_textdomain(static::WIDGET_SLUG, false, $this->resource_manager->langDir());
    } // end widget_textdomain

    /**
     * Fired when the plugin is activated.
     *
     * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public static function activate($network_wide)
    {
        // TODO define activation functionality here
    } // end activate

    /**
     * Fired when the plugin is deactivated.
     *
     * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
     */
    public static function deactivate($network_wide)
    {
        // TODO define deactivation functionality here
    } // end deactivate

    /**
     * @brief      Enqueues an asset for display
     * @param      $end        Indicates front or admin end ('widget' or 'admin')
     * @param      $is_script  Indicates if script or style
     */
    protected function enqueueAsset($end, $is_script)
    {
        if ($is_script) {
            wp_enqueue_script(
                static::WIDGET_SLUG . '-' . $end . '-script',
                $this->resource_manager->distURL() . static::WIDGET_SLUG . '/' . $end . '.js'
            );
        } else {
            wp_enqueue_style(
                static::WIDGET_SLUG . '-' . $end . '-style',
                $this->resource_manager->distURL() . static::WIDGET_SLUG . '/' . $end . '.css'
            );
        }
    }

    /**
     * @brief      Pre-registers the widget assets
     */
    public function registerWidgetAssets()
    {
        $this->registerAsset('widget', $is_script = true);
        $this->registerAsset('widget', $is_script = false);
    }

    /**
     * @brief      Pre-registers the admin assets
     */
    public function registerAdminAssets()
    {
        $this->registerAsset('admin', $is_script = true);
        $this->registerAsset('admin', $is_script = false);
    }

    /**
     * @brief      Returns the output of the widget
     * @return     Widget HTML
     */
    public static function output($template, $args)
    {
        return Article::$blade->make(self::template_name($template), $args)->render();
    }

    /*--------------------------------------------------*/
    /* Private/protected Functions
    /*--------------------------------------------------*/

    /**
     * @brief      Helper function to register an asset
     * @param      $end        Indicates front or admin end ('widget' or 'admin')
     * @param      $is_script  Indicates if script or style
     */
    protected function registerAsset($end, $is_script)
    {
        if ($is_script) {
            wp_register_script(
                static::WIDGET_SLUG . '-' . $end . '-script',
                $this->resource_manager->distURL() . static::WIDGET_SLUG . '/' . $end . '.js'
            );
        } else {
            wp_register_style(
                static::WIDGET_SLUG . '-' . $end . '-style',
                $this->resource_manager->distURL() . static::WIDGET_SLUG . '/' . $end . '.css'
            );
        }
    }

    protected static function template_name($name)
    {
        return static::TEMPLATE_NAMESPACE . '::' . static::WIDGET_SLUG . '-' . $name;
    }

    /**
     * @brief      Gets the arguments for the widget template
     * @param      $args  The array of form elements
     * @return     The processed arguments
     */
    abstract protected function createArgs($args);

    /**
     * @brief      Helper function to get an ACF field for the given widget
     * @param      $args   The Widget instance arguments
     * @param      $field  The field to get
     * @return     The acf field value
     */
    public static function getAcfField($args, $field)
    {
        // We are getting the field on a widget
        return get_field($field, 'widget_' . $args['widget_id']);
    }

    /**
     * @brief      Wraps an array of posts as an array of Article objects
     * @param      $posts  The posts
     * @return     An array of Article objects
     */
    public static function toArticles($posts)
    {
        return array_map(
            function($post) { return new Article($post); },
            $posts);
    }
} // end class
