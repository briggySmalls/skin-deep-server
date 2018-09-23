<?php

namespace SkinDeep\Shop\Donations;

use SkinDeep\Articles\WidgetArgs;
use SkinDeep\Articles\WidgetArgsInterface;
use SkinDeep\Articles\WidgetArgsHelper;

const DONATION_QUERY_VAR = 'donation';

class DonationArgs implements WidgetArgsInterface
{
    public $id = "donation";
    public $title;
    public $price;
    public $url;
    public $description;

    public function __construct($title, $default_price, $description)
    {
        // Record arguments
        $this->title = $title;
        $this->price = $default_price;
        $this->description = $description;

        // Update the URL for the page to add the donation amount
        add_action('init', function () {
            global $wp;
            $wp->add_query_var(DONATION_QUERY_VAR);
        });

        // Now check if a parameter was supplied
        if (get_query_var(DONATION_QUERY_VAR)) {
            // If so update with this
            $this->price = get_query_var('donation');
        }

        // Set URL based on price
        $this->url = get_permalink() . '?' . DONATION_QUERY_VAR . '=' . $this->price;
    }

    public static function fromArgs($args)
    {
        $helper = new WidgetArgsHelper($args);
        return new DonationArgs(
            $helper->getAcfField('sd_shop_donation_title'),
            $helper->getAcfField('sd_shop_default_donation'),
            $helper->getAcfField('sd_shop_donation_description'));
    }
}
