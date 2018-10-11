<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;

class ArchiveSdProduct extends Controller
{
    /**
     * @brief      Set template for a single product in the archive
     * @return     Path of template for product
     */
    public function singlePostTemplate()
    {
        return 'partials.shop.archive-product';
    }

    /**
     * @brief      Number of columns for the product archive
     * @return     Number of columns
     */
    public function columnCount()
    {
        return 4;
    }
}
