<?php

namespace SkinDeep\Theme\Controllers;

use Sober\Controller\Controller;
use SkinDeep\Shop\Product;

class SdShop extends Controller
{
    protected $categories;
    protected $products;

    public function __construct()
    {
        // Request product categories
        $this->categories = get_terms(['taxonomy' => 'sd-product-cat']);
        // Get products per category
        $this->products = [];
        // Prepare query args
        $query_args = [
            'posts_per_page' => 10,
            'post_type' => 'sd-product',
            'tax_query' => [
                [
                    'taxonomy' => 'sd-product-cat',
                    'field' => 'term_id',
                ]
            ]
        ];
        foreach ($this->categories as $term) {
            // Update query for new term and save result
            $query_args['tax_query'][0]['terms'] = $term->term_id;
            $this->products[$term->term_id] = array_map(
                function ($post) {
                    return new Product($post);
                },
                get_posts($query_args)
            );
        }
    }

    public function categories()
    {
        return $this->categories;
    }

    public function columnCount()
    {
        return 4;
    }

    public function products()
    {
        return $this->products;
    }
}
