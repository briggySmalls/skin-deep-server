<?php

namespace SkinDeep\Widgets\PostSuggestions;

use SkinDeep\Articles\Article;
use SkinDeep\Widgets\WidgetArgsInterface;

class PostSuggestionsArgs implements WidgetArgsInterface
{
    public $authors;
    public $categories;

    public function __construct()
    {
        if (!is_single()) {
            // We don't support non-post display
            return;
        }
        // Get the current article
        $article = new Article(get_queried_object());
        // Create args from article
        $this->authors = $article->authors();
        $this->categories = $article->categories();
    }

    public static function fromArgs($args_helper)
    {
        return new PostSuggestionsArgs();
    }
}
