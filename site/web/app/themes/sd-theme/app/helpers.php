<?php

namespace SkinDeep\Theme;

use Roots\Sage\Container;

/**
 * Get the sage container.
 *
 * @param string $abstract
 * @param array  $parameters
 * @param Container $container
 * @return Container|mixed
 */
function sage($abstract = null, $parameters = [], Container $container = null)
{
    $container = $container ?: Container::getInstance();
    if (!$abstract) {
        return $container;
    }
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("sage.{$abstract}", $parameters);
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
        return sage('config');
    }
    if (is_array($key)) {
        return sage('config')->set($key);
    }
    return sage('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = [])
{
    if (!is_admin() && remove_action('wp_head', 'wp_enqueue_scripts', 1)) {
        wp_enqueue_scripts();
    }

    return sage('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = [])
{
    return sage('blade')->compiledPath($file, $data);
}

/**
 * @param $asset
 * @return string
 */
function asset_path($asset)
{
    return sage('assets')->getUri($asset);
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates($templates)
{
    $paths = apply_filters('sage/filter_templates/paths', [
        'views',
        'resources/views'
    ]);
    $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

    return collect($templates)
        ->map(function ($template) use ($paths_pattern) {
            /** Remove .blade.php/.blade/.php from template names */
            $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));

            /** Remove partial $paths from the beginning of template names */
            if (strpos($template, '/')) {
                $template = preg_replace($paths_pattern, '', $template);
            }

            return $template;
        })
        ->flatMap(function ($template) use ($paths) {
            return collect($paths)
                ->flatMap(function ($path) use ($template) {
                    return [
                        "{$path}/{$template}.blade.php",
                        "{$path}/{$template}.php",
                    ];
                })
                ->concat([
                    "{$template}.blade.php",
                    "{$template}.php",
                ]);
        })
        ->filter()
        ->unique()
        ->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates)
{
    return \locate_template(filter_templates($templates));
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', false);
    return $display;
}

/**
 * @brief      Tests if Jetpack's Photon is active
 * @return     true if photon is active, otherwise false
 */
function is_photon_active()
{
    return class_exists('Jetpack') && \Jetpack::is_module_active('photon');
}

/**
 * @brief      Adds additional customizer settings for custom header.
 * @param      $wp_customize  The wp customizer object
 * @return     null
 */
function addCustomHeaderSettings($wp_customize)
{
    // Add a header image background colour setting (DB)
    $wp_customize->add_setting('header_image_bg_colour', [
        'type' => 'theme_mod',
        'default' => '#FFFFFF',
        'capability' => 'edit_theme_options',
        'theme_supports' => 'custom-header',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    // Add colour picker control associated with setting
    $wp_customize->add_control(
        new \WP_Customize_Color_Control(
        $wp_customize,
        'link_color',
        [
            'label'      => __('Header image background color', 'sage'),
            'description' => __('Colour to show either side of image on wide screens.', 'sage'),
            'section'    => 'header_image',
            'settings'   => 'header_image_bg_colour',
        ])
    );

    // Add URL setting
    $wp_customize->add_setting('header_image_url', [
        'type' => 'theme_mod',
        'default' => '',
        'capability' => 'edit_theme_options',
        'theme_supports' => 'custom-header',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    // Add colour picker control associated with setting
    $wp_customize->add_control('header_image_url', [
        'type' => 'url',
        'label' => __('Header image  link', 'sage'),
        'description' => __(nl2br('Make image link to a page.<br/>Note: it\'s preferable for this to be relative, e.g. \'/events\' not \'https://example.com/events\''), 'sage'),
        'placeholder' => __('/articles'),
        'section' => 'header_image',
    ]);
}

/**
 * @brief      Adds customizer settings for a custom logo for a dark page
 * @param      $wp_customize  The wp customizer object
 * @return     null
 */
function addDarkCustomLogo($wp_customize)
{
    $wp_customize->add_setting('logo_dark', [
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint'
    ]);

    // Add media selection control associated with setting
    $wp_customize->add_control(new \WP_Customize_Media_Control($wp_customize, 'image_control', [
      'label' => __( 'Logo - dark', 'sage' ),
      'section' => 'title_tagline',
      'settings' => 'logo_dark',
      'mime_type' => 'image',
    ]));
}

/**
 * @brief      Helper function to determine if a page should be dark themed
 * @return     True if dark page, False otherwise.
 */
function isDarkPage()
{
    // We never care about admin pages
    if (is_admin()) {
        return false;
    }

    // Check if the page has a dark theme
    return has_post_format('video');
}

/**
 * @brief      Gets configuration for a grid of posts.
 * @return     The grid configuration.
 */
function getGridConfig()
{
    return [
        'template' => function ($post) {
            return App::POST_TYPE_MAP[get_post_type($post)]['template'];
        },
        'wrapper' => function ($post) {
            $class_name = App::POST_TYPE_MAP[get_post_type($post)]['wrapper'];
            return new $class_name($post);
        },
        'column_count' => 3,
    ];
}

/**
 * @brief      Render new posts supplied by infinite scroll
 * @return     None
 */
function renderExtraPosts() {
    while (have_posts()) {
        // Update the post
        the_post();
        // Prepare template variables
        $grid_config = getGridConfig();
        $args = [
            'post' => $grid_config['wrapper'](get_post()),
            'grid_config' => getGridConfig(),
        ];
        // Add cards
        echo sage('blade')->make('plugin::partials.card', $args)->render();
    }
}
