<?php

namespace SkinDeep\Articles;

class PostSuggestionsArgs implements WidgetArgsInterface
{
    public $authors;
    public $categories;

    public function __construct($authors, $categories)
    {
        $this->authors = $authors;
        $this->categories = $categories;
    }

    public static function fromArgs($args)
    {
        if (!is_single())
        {
            // We don't support
            return null;
        }
        // Get the current article
        $article = new Article(get_queried_object());
        // Create args from article
        return new PostSuggestionsArgs(
            $article->authors(),
            $article->categories());
    }
}
