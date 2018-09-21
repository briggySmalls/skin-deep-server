<?php

namespace SkinDeep\Articles;

/**
 * Featured posts slider widget
 *
 */
abstract class Widget extends \WP_Widget
{
    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct($title, $description)
    {
        // load plugin text domain
        add_action('init', array( $this, 'widgetTextdomain' ));

        parent::__construct(
            $this->widgetSlug(),
            $title,
            [
                'classname'  => $this->widgetSlug(),
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
        register_activation_hook(__FILE__, ['App\\' . get_class(), 'activate' ]);
        register_deactivation_hook(__FILE__, ['App\\' . get_class(), 'deactivate' ]);
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
        $cache = wp_cache_get($this->widgetSlug(), 'widget');

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

        // Get the context for the template
        $context = $this->createArgs($args);

        // Generate the widget content from the Blade template
        $widget_string .= Article::$blade->make($this->template_name('widget'), ['context' => $context])->render();
        $widget_string .= $after_widget;

        $cache[ $args['widget_id'] ] = $widget_string;

        wp_cache_set($this->widgetSlug(), $cache, 'widget');

        print $widget_string;
    } // end widget

    public function flushWidgetCache()
    {
        wp_cache_delete($this->widgetSlug(), 'widget');
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

        // TODO: Define default values for your variables
        $instance = wp_parse_args(
            (array) $instance
        );

        // TODO: Store the values of the widget in their own variable

        // Display the admin form
        echo Article::$blade->make($this->template_name('admin'))->render();
    } // end form

    /*--------------------------------------------------*/
    /* Public Functions
    /*--------------------------------------------------*/

    /**
     * Loads the Widget's text domain for localization and translation.
     */
    public function widgetTextdomain()
    {

        // TODO be sure to change 'widget-name' to the name of *your* plugin
        load_plugin_textdomain($this->widgetSlug(), false, ResourceManager::langDir());
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
                $this->widgetSlug() . '-' . $end . '-script',
                ResourceManager::distURL() . $this->widgetSlug() . '/' . $end . '.js'
            );
        } else {
            wp_enqueue_style(
                $this->widgetSlug() . '-' . $end . '-style',
                ResourceManager::distURL() . $this->widgetSlug() . '/' . $end . '.css'
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
     * @brief      Helper function to register an asset
     * @param      $end        Indicates front or admin end ('widget' or 'admin')
     * @param      $is_script  Indicates if script or style
     */
    protected function registerAsset($end, $is_script)
    {
        if ($is_script) {
            wp_register_script(
                $this->widgetSlug() . '-' . $end . '-script',
                ResourceManager::distURL() . $this->widgetSlug() . '/' . $end . '.js'
            );
        } else {
            wp_register_style(
                $this->widgetSlug() . '-' . $end . '-style',
                ResourceManager::distURL() . $this->widgetSlug() . '/' . $end . '.css'
            );
        }
    }

    /*--------------------------------------------------*/
    /* Private/protected Functions
    /*--------------------------------------------------*/

    protected function template_name($name)
    {
        return TEMPLATE_NAMESPACE . '::' . $this->widgetSlug() . '-' . $name;
    }

    /**
     * @brief      Gets the name of the widget
     * @return     Name of the widget
     */
    abstract protected function widgetSlug();

    /**
     * @brief      Gets the arguments for the widget template
     * @param      $args  The array of form elements
     * @return     The processed arguments
     */
    abstract protected function createArgs($args);
} // end class
