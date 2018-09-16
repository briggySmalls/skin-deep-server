<?php

namespace SkinDeep\Articles;

class ResourceManager
{
    public static function rootDir($is_relative = false)
    {
        $file_directory = $is_relative ? plugin_basename(__DIR__) : __DIR__;
        return $file_directory . '/../';
    }

    public static function rootURL()
    {
        return plugins_url('/', __DIR__);
    }

    public static function viewDir()
    {
        return self::resourcesDir() . 'views/';
    }

    public static function assetsDir($is_relative = false)
    {
        return self::resourcesDir($is_relative) . 'assets/';
    }

    public static function langDir()
    {
        return self::assetsDir() . 'lang/';
    }

    public static function cacheDir()
    {
        return wp_upload_dir()['basedir'] . '/cache';
    }

    public static function distURL()
    {
        return self::rootURL() . 'dist/';
    }

    public static function resourcesDir($is_relative = false)
    {
        return self::rootDir($is_relative) . 'resources/';
    }

    public static function distDir()
    {
        return self::rootDir() . 'dist/';
    }
}
