<?php

namespace SkinDeep;

use SkinDeep\Events\Plugin;

// Setup articles
require_once(__DIR__ . '/articles/setup.php');

// Setup events
function run()
{
    $plugin = new Plugin();
    $plugin->run();
}
run();
