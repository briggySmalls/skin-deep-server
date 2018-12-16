<?php

namespace SkinDeep;

// Setup articles
require_once(__DIR__ . '/articles/setup.php');

function run()
{
    // Run plugin
    $plugin = new SkinDeep();
    $plugin->run();
}
run();
