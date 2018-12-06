<?php

namespace SkinDeep\Articles;

use \YeEasyAdminNotices\V1\AdminNotice;

use SkinDeep\Widgets\PostsSlider\PostsSliderArgs;
use SkinDeep\Widgets\PostsSlider\PostsSlider;
use SkinDeep\Widgets\PostsPreview\PostsPreviewArgs;
use SkinDeep\Widgets\PostsPreview\PostsPreview;
use SkinDeep\Utilities\ResourceManager;
use SkinDeep\Widgets\BlockArgsHelper;

add_action('plugins_loaded', function () {
    if (!function_exists('get_field')) {
        AdminNotice::create()
            ->error('ACF Pro not found: Skin Deep Articles plugin will not work')
            ->show();
    }
});

// Get the blade engine from sage (for building widget html)
add_action('skin_deep_init', function ($blade) {
    Article::$blade = $blade;
});

// Register the widgets
add_action('widgets_init', function () {
    register_widget('SkinDeep\Widgets\PostsPreview\PostsPreview');
    register_widget('SkinDeep\Widgets\PostsSlider\PostsSlider');
    register_widget('SkinDeep\Widgets\PostSuggestions\PostSuggestions');
});

/**
 * Register blocks
 */
add_action('acf/init', function () {
    // check function exists
    if (function_exists('acf_register_block')) {
        // register posts preview block
        acf_register_block([
            'name'              => 'preview',
            'title'             => __('Posts preview'),
            'description'       => __('Preview filtered posts.'),
            'render_callback'   => __NAMESPACE__ . '\render_preview_posts',
            'category'          => 'widgets',
            'icon'              => 'grid-view',
            'keywords'          => ['posts', 'content'],
        ]);
        // register slider block
        acf_register_block([
            'name'              => 'slider',
            'title'             => __('Posts slider'),
            'description'       => __('Display featured posts in a slider.'),
            'render_callback'   => __NAMESPACE__ . '\render_slider',
            'category'          => 'widgets',
            'icon'              => 'images-alt2',
            'keywords'          => ['posts', 'content'],
        ]);
    }
});

function render_preview_posts()
{
    // Construct arguments
    $args = PostsPreviewArgs::fromArgs(new BlockArgsHelper());
    $arg_array = get_object_vars($args);
    // Generate the 'widget' content
    echo PostsPreview::output(
        new ResourceManager(__DIR__),
        'widget',
        $arg_array
    );
}

function render_slider()
{
    // Construct arguments
    $args = PostsSliderArgs::fromArgs(new BlockArgsHelper());
    $arg_array = get_object_vars($args);
    // Generate the 'widget' content
    echo PostsSlider::output(
        new ResourceManager(__DIR__),
        'widget',
        $arg_array
    );
}

// Remove existing 'authors' base URL
add_filter('author_rewrite_rules', function () {
    return [];
});

// Ensure admins always see the excerpt
add_action('admin_init', function () {
    $user = wp_get_current_user();
    $unchecked = get_user_meta($user->ID, 'metaboxhidden_post', true);
    if ($unchecked) {
        $key = array_search('postexcerpt', $unchecked);
        if (false !== $key) {
            array_splice($unchecked, $key, 1);
            update_user_meta($user->ID, 'metaboxhidden_post', $unchecked);
        }
    }
});

// Rename 'Posts' to 'Articles'
add_action('admin_menu', function () {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Articles';
    if (array_key_exists('edit.php', $submenu)) {
        $submenu['edit.php'][5][0]  = 'Articles';
        if (count($submenu['edit.php']) >= 11) {
            $submenu['edit.php'][10][0] = 'Add Article';
        }
        if (count($submenu['edit.php']) >= 17) {
            $submenu['edit.php'][16][0] = 'Article Tags';
        }
    }
});

// Rename 'Posts' to 'Articles'
add_action('init', function () {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Articles';
    $labels->singular_name = 'Article';
    $labels->add_new = 'Add Article';
    $labels->add_new_item = 'Add Article';
    $labels->edit_item = 'Edit Article';
    $labels->new_item = 'Article';
    $labels->view_item = 'View Article';
    $labels->search_items = 'Search Articles';
    $labels->not_found = 'No Articles found';
    $labels->not_found_in_trash = 'No Articles found in Trash';
    $labels->all_items = 'All Articles';
    $labels->menu_name = 'Articles';
    $labels->name_admin_bar = 'Articles';
});

// Add custom js to editor
add_action('acf/input/admin_enqueue_scripts', function () {
    $resources = new ResourceManager(__DIR__);
    wp_enqueue_script('acf-js', $resources->distURL() . 'acf.js', array(), '1.0.0', true);
});
