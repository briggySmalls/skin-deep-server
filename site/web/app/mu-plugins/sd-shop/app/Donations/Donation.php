<?php

namespace SkinDeep\Shop\Donations;

use SkinDeep\Articles\Widget;
use SkinDeep\Articles\ResourceManager;

class Donation extends Widget
{
    protected const WIDGET_SLUG = 'donation';

    /**
     * Namespace in which blade templates are identified
     */
    protected const TEMPLATE_NAMESPACE = 'shop';

    /**
     * Specifies the classname and description, instantiates the widget,
     * loads localization files, and includes necessary stylesheets and JavaScript.
     */
    public function __construct()
    {
        // Register the widget properties
        parent::__construct(
            __('Donation', self::WIDGET_SLUG),
            __('Donations widget.', self::WIDGET_SLUG),
            new ResourceManager(dirname(__DIR__))
        );
    }

    /**
     * @brief      Factory method for creating args for the widget
     * @param      $args  The arguments
     * @return     Context for the widget
     */
    protected function createArgs($args)
    {
        return DonationArgs::fromArgs($args);
    }
}
