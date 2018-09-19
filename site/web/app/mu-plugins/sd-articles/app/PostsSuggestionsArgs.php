<?php

namespace SkinDeep\Articles;

class PostsSuggestionsArgs extends WidgetArgs
{
    public $args = null;
    public $post = null;
    public $article = null;

    public function __construct($args)
    {
        $this->args = $args;

        // Get the current post
        if (!is_single())
        {
            return;
        }

        // We are only interested in single posts
        $this->article = new Article(get_queried_object());
    }

    public function authors()
    {
        return $this->article->authors();
    }

    public function categories()
    {
        return $this->article->categories();
    }
}
