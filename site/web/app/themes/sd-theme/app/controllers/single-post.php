<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Articles\Article;

class SinglePost extends Controller implements SingleControllerInterface
{
    /**
     * @brief      Wrap the post in convenience object
     * @return     Article object
     */
    public function post()
    {
        return new Article(get_post());
    }

    /**
     * @brief      Indicate the page is in the 'articles' tree
     * @return     True if articles page, False otherwise.
     */
    public function isArticlesPage()
    {
        return true;
    }
}
