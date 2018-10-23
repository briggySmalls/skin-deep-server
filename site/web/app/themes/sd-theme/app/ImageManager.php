<?php

namespace SkinDeep\Theme;

class ImageManager
{
    public const ASPECT_RATIO = 16/9;

    public const WIDTHS = [
        'post-thumbnail' => 240,
        'medium' => 400,
        'medium_large' => 800,
        'large' => 1200,
    ];

    public static function setSrcSizes()
    {
        // Update default sizes to have aspect ratio
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
    }

    public static function addSize($name, $width)
    {
        add_image_size($name, ...self::getImageSizeArgs($width));
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
