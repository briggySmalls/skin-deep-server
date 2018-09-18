<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Articles\Article;

class SinglePost extends Controller
{
    /**
     * @brief      Wrap the post in convenience object
     * @return     Article object
     */
    public function article()
    {
        return new Article(get_post());
    }
}
