<?php
/**
 * Bootstrap wordpress so PHPStan can run
 * See: https://github.com/phpstan/phpstan/issues/35#issuecomment-422044800
 */

// Pretend we are a user visiting the page
$_SERVER['HTTP_HOST'] = "skindeepmag.test";
$_SERVER['SERVER_PROTOCOL'] = "HTTP/1.1";
$_SERVER['REQUEST_METHOD'] = "GET";
$_SERVER['SERVER_NAME'] = "skindeepmag.test";
$_SERVER['REQUEST_URI'] = "/";

ob_start();
require_once(__DIR__ . '/../web/index.php');
ob_end_clean();
