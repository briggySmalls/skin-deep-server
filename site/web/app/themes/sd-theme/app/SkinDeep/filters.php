<?php

namespace SkinDeep\Theme;

use SkinDeep\Articles\Article;
use SkinDeep\Events\Event;
use SkinDeep\Utilities\Helper;

add_filter('wp_nav_menu_args', function ($args) {
    // Use custom nav walker (note: priority 9 to beat soil to it)
    if (!$args['walker']) {
        $args['walker'] = new NavWalker();
    }

    // Add bootstrap navbar class to nav menus
    $args['menu_class'] .= ' navbar-nav';

    return $args;
}, 9);

/**
 * Add bootstrap nav class to menu items
 */
add_filter('nav_menu_css_class', function ($classes, $item) {
    $classes[] = 'nav-item';
    return $classes;
}, 10, 2);

/**
 * Add bootstrap nav class to menu anchors
 */
add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
    // add the desired attributes:
    $atts['class'] = 'nav-link';
    return $atts;
}, 10, 3);

/**
 * Add custom searchform
 */
add_filter('get_search_form', function () {
    return template('partials.searchform');
});

/**
 * Add SnipCart login to primary navigation
 */
add_filter('wp_nav_menu_items', function ($items, $args) {
    if (($args->theme_location == 'primary_navigation') && ($args->depth == 1)) {
        $items .= '<li class="nav-item menu-item snipcart-summary">'
            . '<a href="#" class="snipcart-checkout nav-link">'
            . 'Cart (<span class="snipcart-total-items"></span>)'
            . '</a>'
            . '</li>'
            . '</li>';
    }
    return $items;
}, 10, 2);

/**
 * Add custom caption Note: This is pretty much a straight copy of
 * img_caption_shortcode() in wp-includes/media.php.
 *
 * There is not a nice way to modify the content produced there
 */
add_filter('img_caption_shortcode', function ($current_html, $attr, $content) {
    $atts = shortcode_atts(array(
        'id'      => '',
        'align'   => 'alignnone',
        'width'   => '',
        'caption' => '',
        'class'   => '',
    ), $attr, 'caption');

    // Add custom 'image credit' text
    assert($atts['caption'] !== '');
    assert(preg_match("/^.*_([0-9]+)$/", $attr['id'], $matches)); // Get ID from original atributes (no HTML)
    $attachment_id = $matches[1];
    $credit = get_field('media_credit', $attachment_id);
    if ($credit) {
        // There is an image credit to append
        $credit_text = sprintf("Image credit: %s", $credit);
        if (empty($atts['caption'])) {
            $atts['caption'] = $credit_text;
        } else {
            $atts['caption'] .=  ' - ' . $credit_text;
        }
    }

    $atts['width'] = (int) $atts['width'];
    if ($atts['width'] < 1 || empty($atts['caption'])) {
        return $content;
    }

    if (! empty($atts['id'])) {
        $atts['id'] = 'id="' . esc_attr(sanitize_html_class($atts['id'])) . '" ';
    }

    $class = trim('wp-caption ' . $atts['align'] . ' ' . $atts['class']);

    $html5 = current_theme_supports('html5', 'caption');
    // HTML5 captions never added the extra 10px to the image width
    $width = $html5 ? $atts['width'] : ( 10 + $atts['width'] );

    /**
     * Filters the width of an image's caption.
     *
     * By default, the caption is 10 pixels greater than the width of the image,
     * to prevent post content from running up against a floated image.
     *
     * @since 3.7.0
     *
     * @see img_caption_shortcode()
     *
     * @param int    $width    Width of the caption in pixels. To remove this inline style,
     *                         return zero.
     * @param array  $atts     Attributes of the caption shortcode.
     * @param string $content  The image element, possibly wrapped in a hyperlink.
     */
    $caption_width = apply_filters('img_caption_shortcode_width', $width, $atts, $content);

    $style = '';
    if ($caption_width) {
        $style = 'style="width: ' . (int) $caption_width . 'px" ';
    }

    if ($html5) {
        $html = '<figure ' . $atts['id'] . $style . 'class="' . esc_attr($class) . '">'
        . do_shortcode($content) . '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption></figure>';
    } else {
        $html = '<div ' . $atts['id'] . $style . 'class="' . esc_attr($class) . '">'
        . do_shortcode($content) . '<p class="wp-caption-text">' . $atts['caption'] . '</p></div>';
    }

    return $html;
}, 10, 3);

/**
 * Remove image size attributes from post thumbnails
 */
add_filter('post_thumbnail_html', function ($html) {
    return Helper::removeHeightWidth($html);
}, 10, 1);

/**
 * Disable relative URLs on images if jetpack photon is enabled
 */
add_filter('soil/relative-url-filters', function ($filters) {
    if (is_photon_active()) {
        // Photon CDN is enabled
        return array_diff($filters, ['wp_get_attachment_url']);
    }
    return $filters;
});

/**
 * Override 'sizes' attribute for all images
 */
add_filter('wp_calculate_image_sizes', function ($sizes, $size) {
    // Always assume images are full-width
    return "100vw";
}, 10, 2);

/**
 * Configure posts preview widget
 */
add_filter('sd/articles/preview-config', function ($post_type_config_map) {
    // Create a new grid config using theme templates, etc
    $config = getGridConfig();
    // Keep plugin's column count
    if ($post_type_config_map['column_count']) {
        $config['column_count'] = $post_type_config_map['column_count'];
    }
    return $config;
});

/**
 * Remove tag from archive titles
 */
add_filter('get_the_archive_title', function ($title) {
    if (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    return $title;
});

/**
 * Reveal articles sub-nav bar on article-related pages
 */
add_filter('sd/theme/skip-nav-walking', function ($skip, $element) {
    # Show sub-menu if we are on an article page
    return is_singular('post') || is_home() ? false : $skip;
}, 10, 2);

/**
 * Remove related posts from products (we manually position them in templates)
 */
add_filter('wp', function () {
    if (is_singular('sd-product') && class_exists('Jetpack_RelatedPosts')) {
        $jprp = \Jetpack_RelatedPosts::init();
        $callback = array($jprp, 'filter_add_target_to_dom');
        remove_filter('the_content', $callback, 40);
    }
}, 20);

/**
 * Add custom theme options
 */
add_action('customize_register', function ($wp_customize) {
    // Add the custom header image background colour
    addCustomHeaderSettings($wp_customize);

    // Add second site logo (dark)
    addDarkCustomLogo($wp_customize);
});

/**
 * Fetch dark theme logo image when relevant
 */
add_filter('wp_get_attachment_image_src', function ($image, $attachment_id, $size, $icon) {
    // Check if image is a logo on a dark page
    if ((get_theme_mod('custom_logo') === $attachment_id) && isDarkPage()) {
        // Get the dark theme image details
        $custom_logo_id = get_theme_mod('logo_dark');
        return wp_get_attachment_image_src($custom_logo_id, $size, $icon);
    }
    return $image;
}, 10, 4);

/**
 * @brief      Remove social sharing buttons from default location
 * @return     false
 */
add_filter('loop_start', function () {
    // Copied from https://jetpack.com/support/sharing/
    remove_filter('the_content', 'sharing_display', 19);
    remove_filter('the_excerpt', 'sharing_display', 19);
    if (class_exists('Jetpack_Likes')) {
        remove_filter('the_content', array(Jetpack_Likes::init(), 'post_likes'), 30, 1);
    }
});
