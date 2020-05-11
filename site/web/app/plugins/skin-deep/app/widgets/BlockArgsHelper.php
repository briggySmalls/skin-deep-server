<?php

namespace SkinDeep\Widgets;

class BlockArgsHelper implements ArgsHelperInterface
{
    /**
     * Helper function to get an ACF field for the given widget
     */
    public function getAcfField($field)
    {
        // Blocks just need to call get field
        return get_field($field);
    }
}
