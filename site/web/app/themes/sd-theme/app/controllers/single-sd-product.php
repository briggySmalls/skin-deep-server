<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use SkinDeep\Shop\Product;

class SingleSdProduct extends Controller
{
    public function product()
    {
        return new Product(get_post());
    }
}
