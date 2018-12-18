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
class SkinDeep {
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

    public function __construct() {
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
                'https://www.googletagmanager.com/gtag/js?id=' . getenv('GOOGLE_TRACKING_ID'));
            wp_enqueue_script('skindeep-plugin-public', $resources->distURL() . 'public.js');
        });

        // Add tags to scripts
        $this->loader->addFilter('script_loader_tag', __NAMESPACE__ . '\\SkinDeep::updateScripts', 10, 2);
        // Add preconnect for external assets
        $this->loader->addFilter('wp_resource_hints', __NAMESPACE__ . '\\SkinDeep::preconnectExternalAssets', 10, 2);

        // Register widgets
        $this->loader->addAction('widgets_init', __NAMESPACE__ . '\\SkinDeep::registerWidgets');

        // Do some general setting up
        $this->loader->addAction('wp_print_styles', __NAMESPACE__ . '\\SkinDeep::dequeueDashicons', 100);

        // Create modules
        $this->articles = new ArticlesModule($this->loader);
        $this->events = new EventsModule($this->loader);
        $this->shop = new ShopModule($this->loader);
    }

    /**
     * @brief      Run the loader to execute all of the hooks with WordPress.
     * @return     false
     */
    public function run() {
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
    public static function dequeueDashicons() {
        if (!is_user_logged_in()) {
            // Remove icons if user not logged in
            wp_deregister_style('dashicons');
        }
    }

    public static function preconnectExternalAssets($urls, $relation_type)
    {
        if ($relation_type === 'preconnect') {
            $new_urls = array_map(
                function ($url) {
                    return "//{$url}";
                },
                wp_dependencies_unique_hosts()
            );
            $urls = array_merge($urls, $new_urls);
        }
        return $urls;
    }
}
