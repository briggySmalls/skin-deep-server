<?php

namespace SkinDeep\Theme;

use Sober\Controller\Controller;
use Sober\Controller\Module\Tree;
use SkinDeep\Widgets\PostsPreview\PostsPreview;

const SD_PRODUCT_IMAGE_FACTOR = 3; // Factor to divide card size by to get image size

/**
 * @brief      Class for archive sd product.
 * @note       Inherits from Archive controller
 */
class ArchiveSdProduct extends Controller implements Tree
{
    public static function archiveSizes($post)
    {
        $grid_config = getGridConfig();

        if (has_term('magazines', 'sd-product-cat', $post->ID)) {
            return sprintf(
                '(max-width: %upx) %uvw, (max-width: %upx) %uvw, %upx',
                PostsPreview::BOOTSTRAP_COLUMNS['md'],
                round(100 / SD_PRODUCT_IMAGE_FACTOR),
                PostsPreview::BOOTSTRAP_COLUMNS['xl'],
                round(100 / $grid_config['column_count'] / SD_PRODUCT_IMAGE_FACTOR),
                round(PostsPreview::BOOTSTRAP_COLUMNS['xl'] / $grid_config['column_count'] / SD_PRODUCT_IMAGE_FACTOR)
            );
        } else {
            return PostsPreview::sizes($grid_config['column_count']);
        }
    }
}
