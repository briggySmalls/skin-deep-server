<?php

namespace SkinDeep\Articles;

use SkinDeep\Events\Plugin;
use SkinDeep\Events\Event;

class PostsPreviewArgs implements WidgetArgsInterface
{
    public $posts;
    public $url;
    public $title;
    public $column_count;
    public $grid_config;

    public function __construct($posts, $url, $title, $column_count, $grid_config)
    {
        $this->posts = $posts;
        $this->url = $url;
        $this->title = $title;
        $this->column_count = $column_count;
        $this->grid_config = $grid_config;
    }

    public static function fromArgs($args_helper)
    {
        // Limit query to specified number of posts
        $query_args = [
            'posts_per_page' => (int)$args_helper->getAcfField('sd_widget_preview_count'),
        ];

        $post_type = $args_helper->getAcfField('sd_widget_preview_post_type');

        // Determine what type of filtering we're applying
        switch ($post_type) {
            case 'post':
                list($query_args, $url) = self::getArticleArgs($args_helper, $query_args);
                break;

            case Plugin::EVENT_POST_TYPE:
                list($query_args, $url) = self::getEventArgs($args_helper, $query_args);
                break;

            default:
                assert(false, "Unexpected default");
                break;
        }

        // Get the per-post-type configuration map
        $config = apply_filters('sd/articles/preview-config', []);

        // Now create the argument
        return new PostsPreviewArgs(
            get_posts($query_args),
            $url,
            $args_helper->getAcfField('sd_widget_preview_title'),
            $args_helper->getAcfField('sd_widget_preview_columns'),
            $config
        );
    }

    private static function getArticleArgs($args_helper, $query_args)
    {
        $filter_group = $args_helper->getAcfField('sd_widget_preview_article_filter_group');
        $url = null;
        switch ($filter_group['type']) {
            # Filter articles to the specified category
            case 'category':
                $category = $filter_group['category'];
                $query_args['cat'] = $category;
                $url = get_term_link($category);
                break;

            # Filter articles to the specified format
            case 'format':
                $format = $filter_group['format'];
                $query_args['tax_query'] = [
                    [
                        'taxonomy' => 'post_format',
                        'field' => 'term_id',
                        'terms' => $format->term_id,
                    ]
                ];
                $url = get_post_format_link($format->name);
                break;

            case 'all':
                // Set URL as posts archive
                $url = get_post_type_archive_link('post');
                break;

            default:
                assert(false, "Unexpected default");
                break;
        }
        return [$query_args, $url];
    }

    private static function getEventArgs($args_helper, $query_args)
    {
        $filter_group = $args_helper->getAcfField('sd_widget_preview_event_filter_group');
        switch ($filter_group['type']) {
            case 'status':
                $status = $filter_group['status'];
                $query_args['meta_query'][] = Plugin::getStatusMetaQuery($status);
                $query_args['post_type'] = Plugin::EVENT_POST_TYPE;
                $url = Plugin::getStatusUrl($status);
                break;

            default:
                assert(false, "Unexpected default");
                break;
        }
        return [$query_args, $url];
    }
}
