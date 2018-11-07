<?php

namespace SkinDeep\Shop\Donations;

use SkinDeep\Articles\WidgetArgs;
use SkinDeep\Articles\WidgetArgsInterface;
use SkinDeep\Articles\WidgetArgsHelper;

const MONEY_FORMAT = "%!i";

class DonationArgs implements WidgetArgsInterface
{
    public $id = "donation";
    public $title;
    public $price;
    public $url;
    public $description;

    public const DONATION_QUERY_VAR = 'donation';

    public function __construct($title, $default_price, $description)
    {
        // Record arguments
        $this->title = $title;
        $this->price = self::getPrice($default_price);
        $this->description = $description;

        // Set URL based on price
        $this->url = (
            get_permalink() . '?' .
            self::DONATION_QUERY_VAR . '=' . $this->price * 100);
    }

    public static function fromArgs($args)
    {
        $helper = new WidgetArgsHelper($args);
        return new DonationArgs(
            $helper->getAcfField('sd_shop_donation_title'),
            $helper->getAcfField('sd_shop_default_donation'),
            $helper->getAcfField('sd_shop_donation_description')
        );
    }

    /**
     * @brief      Gets the price from the URL parameters
     * @note       The parameter is in pence (positive integer)
     * @param      $default  The default price in GBP
     * @return     The price in GBP
     */
    public static function getPrice($default)
    {
        // Now check if a parameter was supplied
        if (get_query_var(self::DONATION_QUERY_VAR)) {
            // Validate parameter
            $param = get_query_var(self::DONATION_QUERY_VAR);
            if (is_numeric($param) && $param >= 1 && $param == round($param)) {
                // If so update with this
                return money_format(MONEY_FORMAT, (int)$param / 100);
            }
        }
        return money_format(MONEY_FORMAT, $default);
    }
}
