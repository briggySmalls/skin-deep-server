<?php

namespace SkinDeep\Utilities;

class Helper
{
    /**
     * @brief      Add attribute(s) to HTML tag
     * @param      string $tag         The tag
     * @param      string $attributes  The attributes to add
     * @return     string Update tag
     */
    public static function updateTag(string $tag, string $attributes): string
    {
        return str_replace(' src', "$attributes src", $tag);
    }

    /**
     * @brief      Simple function that removes height/width from HTML
     * @param      $html  The HTML
     * @return     Updated HTML
     */
    public static function removeHeightWidth($html)
    {
        return preg_replace('/(width|height)="\d*"/', '', $html);
    }
}
