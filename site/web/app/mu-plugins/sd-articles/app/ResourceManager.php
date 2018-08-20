<?php

namespace App;

class ResourceManager
{
    static function root_dir($is_relative = false)
    {
        $file_directory = $is_relative ? plugin_basename(__DIR__) : __DIR__;
        return $file_directory . '/../';
    }

    static function root_url()
    {
        return plugins_url('/', __DIR__);
    }

    static function view_dir()
    {
        return self::resources_dir() . 'views/';
    }

    static function assets_dir($is_relative = false)
    {
        return self::resources_dir($is_relative) . 'assets/';
    }

    static function lang_dir()
    {
        return self::assets_dir() . 'lang/';
    }

    static function cache_dir()
    {
        return self::resources_dir() . 'cache/';
    }

    static function dist_url()
    {
        return self::root_url() . 'dist/';
    }

    static function resources_dir($is_relative = false)
    {
        return self::root_dir($is_relative) . 'resources/';
    }

    static function dist_dir()
    {
        return self::root_dir() . 'dist/';
    }
}
