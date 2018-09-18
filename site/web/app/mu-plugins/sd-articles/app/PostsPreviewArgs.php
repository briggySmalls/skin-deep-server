<?php

namespace SkinDeep\Articles;

class PostsPreviewArgs extends WidgetArgs
{

    public $args = null;
    public $posts = null;

    public function __construct($args)
    {
        $this->args = $args;
        $this->posts = $this->toArticles($this->getPreviewPosts($args));
    }

    /**
     * @brief      Helper function to get posts matching widget configuration.
     * @param      $args  The widget output arguments.
     * @return     The posts.
     */
    private function getPreviewPosts()
    {
        // Limit query to specified number of posts
        $query_args = [
            'posts_per_page' => (int)$this->getAcfField('sd_widget_preview_count'),
        ];

        // Determine what type of filtering we're applying
        $filter_group = $this->getAcfField('sd_widget_preview_filter_group');
        switch ($filter_group['sd_widget_preview_filter_type']) {
            # Filter articles to the specified category
            case "category":
                $category = $filter_group['sd_widget_preview_category'];
                $query_args['cat'] = $category;
                $this->url = get_term_link($category);
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
                $this->url = get_post_format_link($format->name);
                break;

            case "all":
                // Set URL as posts archive
                $this->url = get_post_type_archive_link('post');
                break;

            default:
                assert("Unexpected default");
                break;
        }

        // Execute the query
        return get_posts($query_args);
    }
}
