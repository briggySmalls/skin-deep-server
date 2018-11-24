<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Articles\Article;

class SinglePost extends Controller implements SingleControllerInterface
{
    public function post()
    {
        return new Article(get_post());
    }
}
