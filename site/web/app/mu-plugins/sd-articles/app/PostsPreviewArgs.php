<?php

namespace SkinDeep\Articles;

class PostsPreviewArgs implements WidgetArgsInterface
{
    public $posts;
    public $url;
    public $title;
    public $column_count;
    public $post_wrapper_factory;

    public function __construct($posts, $url, $title, $column_count)
    {
        $this->posts = $posts;
        $this->url = $url;
        $this->title = $title;
        $this->column_count = $column_count;
        $this->post_wrapper_factory = ['SkinDeep\Articles\PostsPreviewArgs', 'postWrapperFactory'];
    }

    public static function fromArgs($args)
    {
        // Create a helper
        $helper = new WidgetArgsHelper($args);

        // Limit query to specified number of posts
        $query_args = [
            'posts_per_page' => (int)$helper->getAcfField('sd_widget_preview_count'),
        ];

        // Determine what type of filtering we're applying
        $filter_group = $helper->getAcfField('sd_widget_preview_filter_group');
        $url = null;
        switch ($filter_group['sd_widget_preview_filter_type']) {
            # Filter articles to the specified category
            case "category":
                $category = $filter_group['sd_widget_preview_category'];
                $query_args['cat'] = $category;
                $url = get_term_link($category);
                break;

            # Filter articles to the specified format
            case "format":
                $format = $filter_group['sd_widget_preview_format'];
                $query_args['tax_query'] = [
                    [
                        'taxonomy' => 'post_format',
                        'field' => 'term_id',
                        'terms' => $format->term_id,
                    ]
                ];
                $url = get_post_format_link($format->name);
                break;

            case "all":
                // Set URL as posts archive
                $url = get_post_type_archive_link('post');
                break;

            default:
                assert("Unexpected default");
                break;
        }

        // Now create the argument
        return new PostsPreviewArgs(
            get_posts($query_args),
            $url,
            $helper->getAcfField('sd_widget_preview_title'),
            $helper->getAcfField('sd_widget_preview_columns')
        );
    }

    public static function postWrapperFactory($post)
    {
        return new Article($post);
    }
}
