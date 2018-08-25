<?php

namespace SD_Shop;

use \YeEasyAdminNotices\V1\AdminNotice;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SD_Shop
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SD_Shop
 * @author     Your Name <email@example.com>
 */
class PublicSide
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
     * Snipcart API key
     */
    private $api_key;

    public const SNIPCART_SCRIPT = [
        'handle' => 'snipcart-script',
        'src' => 'https://cdn.snipcart.com/scripts/2.0/snipcart.js',
    ];

    public const SNIPCART_STYLE = [
        'handle' => 'snipcart-style',
        'src' => 'https://cdn.snipcart.com/themes/2.0/base/snipcart.min.css'
    ];

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $sd_shop       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($sd_shop, $version, $loader)
    {
        // Initialise variables
        $this->sd_shop = $sd_shop;
        $this->version = $version;

        // Get the API key when we can
        $loader->addAction('acf/init', function () {
            $this->api_key = get_field('sd_shop_snipcart_api_key', 'option');
            if (!$this->api_key) {
                AdminNotice::create()
                    ->error('Snipcart API key not set. Shop will not function until set in Products > Shop Settings')
                    ->show();
            }
        });

        // Enqueue assets
        $loader->addAction('enqueue_scripts', function () {
            $this->enqueue_scripts();
            $this->enqueue_styles();
        });

        // Customise Snipcart script
        $loader->addFilter('script_loader_tag', [$this, 'scriptLoaderTag'], 10, 3);
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
    {
        wp_enqueue_style(self::SNIPCART_STYLE['handle'], self::SNIPCART_STYLE['src']);
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueScripts()
    {
        wp_enqueue_script(self::SNIPCART_SCRIPT['handle'], self::SNIPCART_SCRIPT['src'], ['jquery']);
    }

    /**
     * @brief      Update script tage for snipcart, including API key in it
     * @param      $tag     The tag
     * @param      $handle  The handle
     * @param      $src     The source
     * @return     Updated tag
     */
    public function scriptLoaderTag($tag, $handle, $src)
    {
        if (self::SNIPCART_SCRIPT['handle'] === $handle) {
            $tag = '<script type="text/javascript" src="' . $src . '" id="snipcart" data-api-key="' . $this->api_key . '"></script>';
        }
        return $tag;
    }
}
