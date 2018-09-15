<?php

namespace App;

use Sober\Controller\Controller;
use App\SkinDeep\Product;

class SingleSdProduct extends Controller
{
    public function product()
    {
        return new Product(get_post());
    }
}
