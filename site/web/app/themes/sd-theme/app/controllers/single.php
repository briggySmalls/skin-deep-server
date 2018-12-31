<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Common\Post;

class Single extends Controller implements SingleControllerInterface
{
    public function post()
    {
        return new Post(get_post());
    }
}
