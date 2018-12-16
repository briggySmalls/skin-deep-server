<?php

namespace SkinDeep\Shop;

use SkinDeep\Module;
use SkinDeep\Widgets\Donations\Donations;
use SkinDeep\Widgets\Donations\DonationArgs;
use SkinDeep\Articles\ResourceManager;
use \YeEasyAdminNotices\V1\AdminNotice;

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
 * Entrypoint for the shop module.
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
class ShopModule extends Module
{
    public function init()
    {
        // Execute setup actions
        $this->defineSitewideHooks();

        // Instantiate public/admin classes
        $i18n = new I18n($this->getLoader());
        $plugin_admin = new AdminSide($this->getLoader());
        $plugin_public = new PublicSide($this->getLoader());
    }

    private function defineSitewideHooks()
    {
        // Register the widgets (donation)
        add_action('widgets_init', function () {
            register_widget('SkinDeep\Widgets\Donations\Donation');
        });

        // Check for ACF
        $this->getLoader()->addAction('plugins_loaded', function () {
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
                Donation::PUBLIC_TEMPLATE,
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
}
