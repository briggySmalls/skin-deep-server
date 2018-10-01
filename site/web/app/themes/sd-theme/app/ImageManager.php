<?php

namespace SkinDeep\Theme;

class ImageManager
{
    const ASPECT_RATIO = 16/9;

    const WIDTHS = [
        'post-thumbnail' => 240,
        'medium' => 400,
        'medium_large' => 800,
        'large' => 1200,
    ];

    public static function setSrcSizes()
    {
        // Update default sizes to have 16by9 aspect ratio
        set_post_thumbnail_size(...self::getImageSizeArgs(self::WIDTHS['post-thumbnail']));
        self::updateSize('medium', self::WIDTHS['medium']);
        self::updateSize('medium_large', self::WIDTHS['medium_large']);
        self::updateSize('large', self::WIDTHS['large']);
    }

    public static function getImageSizeArgs($width)
    {
        return array_values(self::sizeArgs($width));
    }

    public static function updateSize($name, $width)
    {
        $args = self::sizeArgs($width);
        // Update the option (just to reflect the hard-coded values)
        update_option("${name}_size_w", $args['width']);
        update_option("${name}_size_h", $args['height']);
        update_option("${name}_crop", $args['crop']);
        // 'Add' a new size over the default, so we can specify crop
        // self::addSize($name, ...array_values($args));
    }

    public static function addSize($name, $width)
    {
        add_image_size($name, ...self::getImageSizeArgs($width));
    }

    public static function image($image_id)
    {
        $img_src = wp_get_attachment_image_url($image_id, 'full');
        $img_srcset = wp_get_attachment_image_srcset($image_id, 'medium_large');
        $img_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true); ?>
        <img src="<?php echo esc_attr($img_src); ?>" srcset="<?php echo esc_attr($img_srcset); ?>" sizes="100vw" alt="<?php echo $img_alt; ?>">
        <?php
    }

    protected static function sizeArgs($width)
    {
        return [
            'width' => $width,
            'height' => round($width / self::ASPECT_RATIO),
            'crop' => true
        ];
    }
}
