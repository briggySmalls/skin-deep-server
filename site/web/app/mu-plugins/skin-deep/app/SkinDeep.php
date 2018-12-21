<?php

namespace SkinDeep;

use \YeEasyAdminNotices\V1\AdminNotice;

use SkinDeep\Utilities\ResourceManager;
use SkinDeep\Utilities\Helper;
use SkinDeep\Utilities\Loader;
use SkinDeep\Events\EventsModule;
use SkinDeep\Shop\ShopModule;
use SkinDeep\Articles\ArticlesModule;

/**
 * @brief      Entrypoint for the skin deep plugin
 */
class SkinDeep
{
    /**
     * Helper class for managing actions/filters for the whole plugin
     */
    private $loader;

    /**
     * Module for managing shop functionality
     */
    private $shop;

    /**
     * Module for managing event functionality
     */
    private $events;

    /**
     * Current plugin version.
     */
    public const VERSION = '1.0.0';

    /**
     * Sources on which to add crossorigin attribute to resource hint
     */
    const CROSSORIGIN_SOURCES = [
        '//fonts.googleapis.com'
    ];

    public function __construct()
    {
        // Create a new loader
        $this->loader = new Loader();

        // Ensure ACF is present
        $this->loader->addAction('plugins_loaded', function () {
            if (!function_exists('get_field')) {
                AdminNotice::create()
                    ->error('ACF Pro not found: Skin Deep plugin will not work. Contact site admin.')
                    ->show();
            }
        });

        // Register public scripts & styles
        $resources = new ResourceManager(__DIR__);
        $this->loader->addAction('wp_enqueue_scripts', function () use ($resources) {
            wp_enqueue_script(
                'google-tag-manager',
                'https://www.googletagmanager.com/gtag/js?id=' . getenv('GOOGLE_TRACKING_ID')
            );
            wp_enqueue_script('skindeep-plugin-public', $resources->distURL() . 'public.js');
        });

        // Add tags to scripts
        $this->loader->addFilter('script_loader_tag', self::staticMethod('updateScripts'), 10, 2);
        // Add preconnect for external assets
        $this->loader->addFilter('wp_resource_hints', self::staticMethod('preconnectExternalAssets'), 10, 2);

        // Register widgets
        $this->loader->addAction('widgets_init', self::staticMethod('registerWidgets'));

        // Do some general setting up
        $this->loader->addAction('wp_print_styles', self::staticMethod('dequeueDashicons'), 100);

        // Remove native taxonomy field from attachments (we use ACF)
        $this->loader->AddFilter('register_taxonomy_args', self::staticMethod('removeNativeArtist'), 10, 2);

        // Create modules
        $this->articles = new ArticlesModule($this->loader);
        $this->events = new EventsModule($this->loader);
        $this->shop = new ShopModule($this->loader);
    }

    /**
     * @brief      Run the loader to execute all of the hooks with WordPress.
     * @return     false
     */
    public function run()
    {
        // Execute all actions/filters
        $this->loader->run();
    }

    /**
     * @brief      Register plugin's widgets
     * @return     false
     */
    public static function registerWidgets()
    {
        register_widget('SkinDeep\Widgets\PostsPreview\PostsPreview');
        register_widget('SkinDeep\Widgets\PostsSlider\PostsSlider');
        register_widget('SkinDeep\Widgets\PostSuggestions\PostSuggestions');
        register_widget('SkinDeep\Widgets\Donations\Donation');
    }

    /**
     * @brief      Add attributes to scripts
     * @param      $tag     The script tag HTML
     * @param      $handle  The handle of the script
     * @return     The filtered script tag HTML
     */
    public static function updateScripts($tag, $handle)
    {
        $scripts = [
            'google-tag-manager' => 'async',
        ];

        if (isset($scripts[$handle])) {
            return Helper::updateTag($tag, $scripts[$handle]);
        }
        return $tag;
    }

    /**
     * @brief      Dequeues the Dashicons CSS from the frontend
     * @return     None
     */
    public static function dequeueDashicons()
    {
        if (!is_user_logged_in()) {
            // Remove icons if user not logged in
            wp_deregister_style('dashicons');
        }
    }

    /**
     * @brief      Add preconnect browser hints
     * @note       Wordpress already adds dns-prefetch for all external links
     * @param      $urls           The urls
     * @param      $relation_type  The relation type
     * @return     urls/attributes for resource hinting
     */
    public static function preconnectExternalAssets($urls, $relation_type)
    {
        if ($relation_type === 'preconnect') {
            // Create preconnect URLs from unique hosts
            $new_urls = array_map(
                function ($url) {
                    // Make URL relative protocol (http/https depending on connection)
                    $entry = ['href' => "//{$url}"];
                    return $entry;
                },
                wp_dependencies_unique_hosts()
            );
            // Add the new preconnect URLs to any existing ones
            $urls = array_merge($urls, $new_urls);
            // Add special preconnect for google fonts
            $urls[] = [
                'href' => '//fonts.gstatic.com',
                'crossorigin',
            ];
        }
        return $urls;
    }

    public static function removeNativeArtist($args, $taxonomy) {
        if ($taxonomy === 'sd_artist') {
            $args['meta_box_cb'] = false;
        }
        return $args;
    }

    /**
     * @brief      Helper method to build static method string
     * @param      $name  The name of the function
     * @return     Static method string
     */
    private static function staticMethod($name) {
        return __NAMESPACE__ . "\\SkinDeep::$name";
    }
}
