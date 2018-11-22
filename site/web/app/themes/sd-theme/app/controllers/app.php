<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Articles\Post;
use SkinDeep\Articles\Article;
use SkinDeep\Events\Event;
use SkinDeep\Shop\Product;

class App extends Controller
{
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

    public function postWrapperFactory()
    {
        if (is_home()) {
            /* This is an archive of articles (blog page)
             * NOTE: home is a bit of a misnomer
             */
            return function ($post) {
                return new Article($post);
            };
        } elseif (is_search()) {
            return function ($post) {
                switch (get_post_type()) {
                    case 'post':
                        return new Article($post);
                        break;

                    case 'sd-product':
                        return new Product($post);
                        break;

                    case 'sd-event':
                        return new Event($post);
                        break;

                    default:
                        return new Post($post);
                        break;
                }
            };
        }
        return function ($post) {
            return new Post($post);
        };
    }

    protected static function category()
    {
        assert(is_category());
        return get_queried_object();
    }
}
