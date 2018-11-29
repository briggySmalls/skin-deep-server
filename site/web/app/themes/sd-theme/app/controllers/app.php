<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;

class App extends Controller
{
    public const POST_TYPE_MAP = [
        'post' => [
            'template' => 'partials.archive.post',
            'wrapper' => 'SkinDeep\\Articles\\Article',
        ],
        'sd-event' => [
            'template' => 'partials.archive.event',
            'wrapper' => 'SkinDeep\\Events\\Event',
        ],
        'sd-product' => [
            'template' => 'partials.archive.product',
            'wrapper' => 'SkinDeep\\Shop\\Product',
        ],
        'page' => [
            'template' => 'partials.archive.post',
            'wrapper' => 'SkinDeep\\Articles\\Post',
        ]
    ];

    public function siteName()
    {
        return get_bloginfo('name');
    }

    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }

    public static function image($size)
    {
        if (is_category()) {
            $image = get_field('sd_article_category_image', self::category());
            return wp_get_attachment_image($image, $size, false);
        }
        return null;
    }

    public static function description()
    {
        if (is_category()) {
            return get_field('sd_article_category_description', self::category());
        }
        return null;
    }

    public static function isDefaultCategory($category)
    {
        $default_category = get_option('default_category');
        return $default_category == $category->term_id;
    }

    public function columnCount()
    {
        return 3;
    }

    public function gridConfig()
    {
        return [
            'template' => function ($post) {
                return App::POST_TYPE_MAP[get_post_type($post)]['template'];
            },
            'wrapper' => function ($post) {
                $class_name = App::POST_TYPE_MAP[get_post_type($post)]['wrapper'];
                return new $class_name($post);
            },
        ];
    }

    protected static function category()
    {
        assert(is_category());
        return get_queried_object();
    }
}
