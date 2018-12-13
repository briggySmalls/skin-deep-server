<?php

namespace SkinDeep\Widgets\Donations;

use SkinDeep\Widgets\Widget;

class Donation extends Widget
{
    protected const WIDGET_SLUG = 'donation';

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct()
    {
        // Register the widget properties
        parent::__construct(
            __('Donation', self::WIDGET_SLUG),
            __('Donations widget.', self::WIDGET_SLUG)
        );
    }

    /**
     * @brief      Factory method for creating args for the widget
     * @param      $args  The arguments
     * @return     Context for the widget
     */
    protected function createArgs($args_helper)
    {
        return DonationArgs::fromArgs($args_helper);
    }
}
