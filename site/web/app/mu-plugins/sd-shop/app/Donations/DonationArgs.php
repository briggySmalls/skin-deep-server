<?php

namespace SkinDeep\Shop\Donations;

use SkinDeep\Articles\WidgetArgs;

const DONATION_QUERY_VAR = 'donation';

class DonationArgs extends WidgetArgs
{
    public $args = null;
    public $posts = null;

    public function __construct($args)
    {
        // Record the arguments
        $this->args = $args;

        // Update the URL for the page to add the donation amount
        add_action('init','add_get_val');
        function add_get_val() {
            global $wp;
            $wp->add_query_var(DONATION_QUERY_VAR);
        }
    }

    public function id()
    {
        return $this->args['widget_id'];
    }

    public function title()
    {
        return $this->getAcfField('sd_shop_donation_title');
    }

    public function price()
    {
        // First assume the default
        $donation = $this->getAcfField('sd_shop_default_donation');
        // Now check if a parameter was supplied
        if (get_query_var(DONATION_QUERY_VAR)) {
            // If so update with this
            $donation = get_query_var('donation');
        }
        return $donation;
    }

    public function url()
    {
        // Set the query for validating the donation
        // Note: This will be updated in javascript
        $donation = $this->getAcfField('sd_shop_default_donation');
        return get_permalink() . '?' . DONATION_QUERY_VAR . '=' . $this->price();
    }

    public function description()
    {
        return $this->getAcfField('sd_shop_donation_description');
    }
}
