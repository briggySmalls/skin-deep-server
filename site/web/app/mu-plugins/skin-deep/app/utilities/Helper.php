<?php

namespace SkinDeep\Utilities;

class Helper
{
    /**
     * @brief      Add attribute(s) to HTML tag
     * @param      $tag         The tag
     * @param      $attributes  The attributes to add
     * @return     Update tag
     */
    public static function updateTag($tag, $attributes)
    {
        return str_replace(' src', "$attributes src", $tag);
    }
}
