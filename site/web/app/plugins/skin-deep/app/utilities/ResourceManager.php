<?php

namespace SkinDeep\Utilities;

class ResourceManager
{
    protected $path;

    public function rootDir($is_relative = false)
    {
        $file_directory = $is_relative ? plugin_basename(__DIR__) : __DIR__;
        return $file_directory . '/../../';
    }

    public function rootURL()
    {
        return plugins_url('/../', __DIR__);
    }

    public function viewDir()
    {
        return self::resourcesDir() . 'views/';
    }

    public function assetsDir($is_relative = false)
    {
        return self::resourcesDir($is_relative) . 'assets/';
    }

    public function langDir()
    {
        return self::assetsDir() . 'lang/';
    }

    public function cacheDir()
    {
        return wp_upload_dir()['basedir'] . '/cache';
    }

    public function distURL()
    {
        return self::rootURL() . 'dist/';
    }

    public function resourcesDir($is_relative = false)
    {
        return self::rootDir($is_relative) . 'resources/';
    }

    public function distDir()
    {
        return self::rootDir() . 'dist/';
    }
}
