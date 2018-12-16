<?php

namespace SkinDeep\Articles;

use SkinDeep\Module;
use SkinDeep\Utilities\ResourceManager;
use SkinDeep\Widgets\PostsSlider\PostsSlider;
use SkinDeep\Widgets\PostsSlider\PostsSliderArgs;
use SkinDeep\Widgets\PostsPreview\PostsPreview;
use SkinDeep\Widgets\PostsPreview\PostsPreviewArgs;
use SkinDeep\Widgets\PostSuggestions\PostSuggestions;
use SkinDeep\Widgets\PostSuggestions\PostSuggestionsArgs;
use SkinDeep\Widgets\BlockArgsHelper;

class ArticlesModule extends Module
{
    protected function init() {
        // Ensure ACF is present
        $this->getLoader()->addAction('plugins_loaded', function () {
            if (!function_exists('get_field')) {
                AdminNotice::create()
                    ->error('ACF Pro not found: Skin Deep Articles plugin will not work')
                    ->show();
            }
        });

        // Register article widgets
        $this->getLoader()->addAction('widgets_init', function () {
            register_widget('SkinDeep\Widgets\PostsPreview\PostsPreview');
            register_widget('SkinDeep\Widgets\PostsSlider\PostsSlider');
            register_widget('SkinDeep\Widgets\PostSuggestions\PostSuggestions');
        });

        // Remove existing 'authors' base URL
        $this->getLoader()->addFilter('author_rewrite_rules', function () {
            return [];
        });

        // Ensure admins always see the excerpt
        $this->getLoader()->addAction('admin_init', function () {
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
        $this->getLoader()->addAction('admin_menu', function () {
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
        $this->getLoader()->addAction('init', function () {
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

        /**
         * Register blocks
         */
        $this->getLoader()->addAction('acf/init', function () {
            // check function exists
            if (function_exists('acf_register_block')) {
                // register posts preview block
                acf_register_block(self::getPreviewPostsBlockConfig());
                // register slider block
                acf_register_block(self::getSliderBlockConfig());
            }
        });

        // Add custom js to editor
        $this->getLoader()->addAction('acf/input/admin_enqueue_scripts', function () {
            $resources = new ResourceManager(__DIR__);
            wp_enqueue_script('acf-js', $resources->distURL() . 'acf.js', array(), '1.0.0', true);
        });

        // Add suggestions after all articles
        $this->getLoader()->addFilter('the_content', __NAMESPACE__ . '\\ArticlesModule::updateContent', 40);
    }

    /**
     * @brief      Callback for rendering the preview posts block in Gutenberg
     * @note       Echos the content
     * @return     false
     */
    public static function renderPreviewPosts()
    {
        // Construct arguments
        $args = PostsPreviewArgs::fromArgs(new BlockArgsHelper());
        $arg_array = get_object_vars($args);
        // Generate the widget content
        echo PostsPreview::output(
            new ResourceManager(),
            PostsPreview::PUBLIC_TEMPLATE,
            $arg_array
        );
    }

    /**
     * @brief      Callback for rendering the posts slider block in Gutenberg
     * @note       Echos the content
     * @return     false
     */
    public static function renderSlider()
    {
        // Construct arguments
        $args = PostsSliderArgs::fromArgs(new BlockArgsHelper());
        $arg_array = get_object_vars($args);
        // Generate the 'widget' content
        echo PostsSlider::output(
            new ResourceManager(),
            PostsSlider::PUBLIC_TEMPLATE,
            $arg_array
        );
    }

    /**
     * @brief      Callback for filtering an article's content
     * @param      $content  The content
     * @return     The filtered content
     */
    public static function updateContent($content)
    {
        // Add suggestions widget before jetpack's related posts
        if (is_singular('post')) {
            $args = PostSuggestionsArgs::fromArgs(null);
            $arg_array = get_object_vars($args);
            // Generate the 'widget' content
            $content .= PostSuggestions::output(
                new ResourceManager(),
                PostSuggestions::PUBLIC_TEMPLATE,
                $arg_array
            );
        }
        return $content;
    }

    /**
     * @brief      Gets the preview posts block configuration.
     * @return     The preview posts block configuration.
     */
    private static function getPreviewPostsBlockConfig()
    {
        return [
            'name'              => 'preview',
            'title'             => __('Posts preview'),
            'description'       => __('Preview filtered posts.'),
            'render_callback'   => __NAMESPACE__ . '\ArticlesModule::renderPreviewPosts',
            'category'          => 'widgets',
            'icon'              => 'grid-view',
            'keywords'          => ['posts', 'content'],
        ];
    }

    /**
     * @brief      Gets the slider block configuration.
     * @return     The slider block configuration.
     */
    private static function getSliderBlockConfig()
    {
        return [
            'name'              => 'slider',
            'title'             => __('Posts slider'),
            'description'       => __('Display featured posts in a slider.'),
            'render_callback'   => __NAMESPACE__ . '\ArticlesModule::renderSlider',
            'category'          => 'widgets',
            'icon'              => 'images-alt2',
            'keywords'          => ['posts', 'content'],
        ];
    }
}
