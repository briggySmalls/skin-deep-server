<?php

namespace SkinDeep;

use SkinDeep\Events\Plugin;
use SkinDeep\Shop\Shop;

// Setup articles
require_once(__DIR__ . '/articles/setup.php');

function run()
{
    // Run events
    $plugin = new Plugin();
    $plugin->run();

    // Run shop
    $plugin = new Shop();
    $plugin->run();
}
run();
