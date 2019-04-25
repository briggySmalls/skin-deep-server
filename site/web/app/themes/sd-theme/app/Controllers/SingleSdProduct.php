<?php

namespace SkinDeep\Theme\Controllers;

use Sober\Controller\Controller;
use SkinDeep\Shop\Product;

class SingleSdProduct extends Controller implements SingleControllerInterface
{
    public function post()
    {
        return new Product(get_post());
    }
}
