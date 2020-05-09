<?php

namespace SkinDeep\Shop;

use \YeEasyAdminNotices\V1\AdminNotice;

use SkinDeep\Widgets\Donations\DonationArgs;
use SkinDeep\Utilities\Helper;
use SkinDeep\Utilities\ResourceManager;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    SkinDeep\Shop
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SkinDeep\Shop
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

    //! Name for Skin Deep's script that customises SnipCart
    public const CUSTOM_SNIPCART_SCRIPT_HANDLE = 'custom-snipcart-script';

    //! Field name for SnipCart API key option
    private const SNIPCART_API_KEY_FIELD = 'sd_shop_snipcart_api_key';

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $sd_shop       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($loader)
    {
        // Enqueue assets
        $loader->addAction('wp_enqueue_scripts', function () {
            $this->enqueueScripts();
            $this->enqueueStyles();
        });

        // Customise Snipcart script
        $loader->addFilter('script_loader_tag', [$this, 'scriptLoaderTag'], 10, 3);

        // Add a query var for donations
        $loader->addAction('init', function () {
            global $wp;
            $wp->add_query_var(DonationArgs::DONATION_QUERY_VAR);
        });

        // Get the API key when we can
        $loader->addAction('acf/init', function () {
            $this->api_key = get_field(self::SNIPCART_API_KEY_FIELD, 'option');
            if (!$this->api_key) {
                AdminNotice::create()
                    ->error('Snipcart API key not set. Shop will not function until set in Products > Shop Settings')
                    ->show();
            }
        });
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
        $resources = new ResourceManager(__DIR__);
        wp_register_script(
            self::SNIPCART_SCRIPT['handle'],
            self::SNIPCART_SCRIPT['src'],
            ['jquery'],
            null,
            true
        );

        // Enqueue SnipCart customiser that modifies cart (automatically enqueues snipcat itself)
        wp_enqueue_script(
            self::CUSTOM_SNIPCART_SCRIPT_HANDLE,
            $resources->distUrl() . 'customise-snipcart.js',
            [self::SNIPCART_SCRIPT['handle']]
        );
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
            // Rebuild tag, providing the API key
            return "<script type=\"text/javascript\" src=\"$src\" id=\"snipcart\" data-api-key=\"{$this->api_key}\" async></script>";
        } elseif ($handle === self::CUSTOM_SNIPCART_SCRIPT_HANDLE) {
            return Helper::updateTag($tag, "async");
        }
        return $tag;
    }
}
