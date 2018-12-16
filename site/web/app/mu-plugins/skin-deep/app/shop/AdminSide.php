<?php

namespace SkinDeep\Shop;

use SkinDeep\Widgets\BlockArgsHelper;
use SkinDeep\Utilities\ResourceManager;
use SkinDeep\Widgets\Donations\Donation;
use SkinDeep\Widgets\Donations\DonationArgs;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SkinDeep\Shop
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SkinDeep\Shop
 * @author     Your Name <email@example.com>
 */
class AdminSide
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $sd_shop    The ID of this plugin.
     */
    private $sd_shop;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $sd_shop       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($sd_shop, $version, $loader)
    {
        $this->sd_shop = $sd_shop;
        $this->version = $version;

        // Enqueue assets
        $loader->addAction('admin_enqueue_scripts', function () {
            $this->enqueueScripts();
            $this->enqueueStyles();
        });

        // Register donation block
        $loader->addAction('acf/init', function () {
            // check function exists
            if (function_exists('acf_register_block')) {
                // register posts preview block
                acf_register_block([
                    'name'              => 'donation',
                    'title'             => __('Donation'),
                    'description'       => __('Form for donations.'),
                    'render_callback'   => __NAMESPACE__ . '\\AdminSide::renderDonation',
                    'category'          => 'widgets',
                    'icon'              => 'grid-view',
                    'keywords'          => ['donation', 'e-commerce'],
                ]);
            }
        });
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
    {
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueueScripts()
    {
    }

    public static function renderDonation()
    {
        // Construct arguments
        $args = DonationArgs::fromArgs(new BlockArgsHelper());
        $arg_array = get_object_vars($args);
        // Generate the 'widget' content
        echo Donation::output(
            new ResourceManager(__DIR__),
            Donation::PUBLIC_TEMPLATE,
            $arg_array
        );
    }
}
