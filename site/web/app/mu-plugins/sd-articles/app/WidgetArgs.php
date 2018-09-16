<?php

namespace SkinDeep\Articles;

abstract class WidgetArgs
{
    /**
     * Helper function to get an ACF field for the given widget
     */
    public function getAcfField($field)
    {
        return get_field($field, 'widget_' . $this->args['widget_id']);
    }
}
