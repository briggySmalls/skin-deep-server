<?php

namespace App;

class ResourceManager {
    static function view_dir () {
        return self::resources_dir() . 'views/';
    }

    static function assets_dir ( $is_relative=False ) {
        return self::resources_dir( $is_relative ) . 'assets/';
    }

    static function lang_dir () {
        return self::assets_dir() . 'lang/';
    }

    static function cache_dir () {
        return self::resources_dir() . 'cache/';
    }

    static function view_file ( $filename ) {
        return self::resources_dir() . 'views/' . $filename;
    }

    static function asset_url ( $filename ) {
        return plugins_url( self::assets_dir( $is_relative = True ) . $filename );
    }

    static function resources_dir ( $is_relative=False ) {
        $file_directory = $is_relative ? plugin_basename( __DIR__ ) : __DIR__;
        return $file_directory . '/../resources/';
    }
}
