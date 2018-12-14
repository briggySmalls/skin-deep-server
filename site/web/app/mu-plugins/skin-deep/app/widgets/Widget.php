<?php

namespace SkinDeep\Widgets;

use function SkinDeep\Theme\sage;

use SkinDeep\Utilities\ResourceManager;

/**
 * Featured posts slider widget
 *
 */
abstract class Widget extends \WP_Widget
{
    protected $resource_manager;

    /**
     * Namespace in which blade templates are identified
     */
    protected const TEMPLATE_NAMESPACE = 'plugin';

    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct($title, $description)
    {
        $this->resource_manager = new ResourceManager();

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

        // go on with your widget logic, put everything into a string and …
        extract($args, EXTR_SKIP);
        $widget_string = $before_widget;

        // Generate the widget content from the Blade template
        $context = get_object_vars($this->createArgs(new WidgetArgsHelper($args)));
        $widget_string .= self::output($this->resource_manager, 'widget', $context);
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
        // Display the admin form
        echo self::output($this->resource_manager, 'admin', null);
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
    protected static function enqueueAsset($resource_manager, $end, $is_script)
    {
        if ($is_script) {
            wp_enqueue_script(
                $end . '-script',
                $resource_manager->distURL() . $end . '.js',
                ['jquery']
            );
        } else {
            wp_enqueue_style(
                $end . '-style',
                $resource_manager->distURL() . $end . '.css'
            );
        }
    }

    /**
     * @brief      Returns the output of the widget
     * @param      $resource_manager  The resource manager
     * @param      $template          The template
     * @param      $args              The arguments
     * @return     Widget HTML
     */
    public static function output($resource_manager, $template, $args)
    {
        // Ensure we have access to the blade template
        if (!function_exists('SkinDeep\Theme\sage')) {
            return false;
        }

        // Enqueue styles and scripts now we are going to use them
        self::enqueueAsset($resource_manager, $template, $is_script = true);
        self::enqueueAsset($resource_manager, $template, $is_script = false);

        // Generate the output
        if ($args) {
            // Pass context variables to template
            return sage('blade')->make(self::templateName($template), $args)->render();
        }
        return sage('blade')->make(self::templateName($template))->render();
    }

    /*--------------------------------------------------*/
    /* Private/protected Functions
    /*--------------------------------------------------*/

    protected static function templateName($name)
    {
        return static::TEMPLATE_NAMESPACE . '::' . static::WIDGET_SLUG . '-' . $name;
    }

    /**
     * @brief      Gets the arguments for the widget template
     * @param      $args_helper  The widget args accessible from the helper
     * @return     The processed arguments
     */
    abstract protected function createArgs($args_helper);

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
} // end class
