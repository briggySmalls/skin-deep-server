<?php

namespace SkinDeep\Shop;

use SkinDeep\Widgets\Donations\Donations;
use SkinDeep\Widgets\Donations\DonationArgs;
use SkinDeep\Articles\ResourceManager;
use \YeEasyAdminNotices\V1\AdminNotice;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
const SD_SHOP_VERSION = '1.0.0';

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SkinDeep\Shop
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    SkinDeep\Shop
 * @author     Your Name <email@example.com>
 */
class Shop
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $sd_shop    The string used to uniquely identify this plugin.
     */
    protected $sd_shop;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        // Initialise variables
        $this->version = SD_SHOP_VERSION;
        $this->sd_shop = 'sd-shop';
        $this->loader = new Loader();

        // Execute setup actions
        $this->defineSitewideHooks();

        // Instantiate public/admin classes
        $i18n = new I18n($this->loader);
        $plugin_admin = new AdminSide($this->getSdShop(), $this->getVersion(), $this->loader);
        $plugin_public = new PublicSide($this->getSdShop(), $this->getVersion(), $this->loader);
    }

    private function defineSitewideHooks()
    {
        // Register the widgets (donation)
        add_action('widgets_init', function () {
            register_widget('SkinDeep\Widgets\Donations\Donation');
        });

        // Check for ACF
        $this->loader->addAction('plugins_loaded', function () {
            if (!function_exists('get_field')) {
                AdminNotice::create()
                    ->error('ACF Pro not found: Skin Deep Shop plugin will not work')
                    ->show();
            }
        });

        // Register shortcode (donation)
        add_shortcode('donation', function ($atts) {
            // Construct arguments
            $args = new DonationArgs(
                $atts['title'],
                $atts['default_donation'],
                array_key_exists('description', $atts) ? $atts['description'] : null
            );
            $arg_array = get_object_vars($args);
            // Generate the 'widget' content
            return Donation::output(
                new ResourceManager(__DIR__),
                'widget',
                $arg_array
            );
        });

        add_action('pre_get_posts', function ($query) {
            if (!is_admin() && // Do not mess up admin lists
                    $query->is_main_query() && // Preserve menus etc.
                    is_post_type_archive('sd-product')) {
                // Order products by stock status (and then date within that)
                $query->set(
                    'orderby',
                    [
                        'meta_value' => 'DESC',
                        'date' => 'DESC'
                    ]
                );
                $query->set('meta_key', 'sd_product_in_stock');
            }
        });
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function getSdShop()
    {
        return $this->sd_shop;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Loader    Orchestrates the hooks of the plugin.
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function getVersion()
    {
        return $this->version;
    }
}
