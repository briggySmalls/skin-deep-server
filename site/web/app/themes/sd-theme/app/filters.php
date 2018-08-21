<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__.'\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);
    if ($template) {
        echo template($template, $data);
        return get_stylesheet_directory().'/index.php';
    }
    return $template;
}, PHP_INT_MAX);

/**
 * Tell WordPress how to find the compiled path of comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
    $comments_template = str_replace(
        [get_stylesheet_directory(), get_template_directory()],
        '',
        $comments_template
    );
    return template_path(locate_template(["views/{$comments_template}", $comments_template]) ?: $comments_template);
}, 100);

/**
 * Add bootstrap nav class to menu anchors
 */
add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
    // check if the item is in the primary menu
    if ($args->theme_location == 'primary_navigation') {
      // add the desired attributes:
        $atts['class'] = 'nav-link';
    }
    return $atts;
}, 10, 3);

/**
 * Add custom searchform
 */
add_filter('get_search_form', function () {
    return \App\template('partials.searchform');
});

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
