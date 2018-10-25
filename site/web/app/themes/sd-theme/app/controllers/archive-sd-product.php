<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;

const SD_PRODUCT_COLUMN_COUNT = 3;
const SD_PRODUCT_TEMPLATE = 'partials.shop.archive-product';

class ArchiveSdProduct extends Controller
{
    /**
     * @brief      Set template for a single product in the archive
     * @return     Path of template for product
     */
    public function singlePostTemplate()
    {
        return SD_PRODUCT_TEMPLATE;
    }

    /**
     * @brief      Number of columns for the product archive
     * @return     Number of columns
     */
    public function columnCount()
    {
        return SD_PRODUCT_COLUMN_COUNT;
    }
}
