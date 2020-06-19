<?php
namespace dimonka2\flatform;

use dimonka2\flatform\Form\Context;
class FlatformService
{
    // partial view locations

    private static $cssList = [];
    private static $jsList = [];
    private static $includes = [];
    private static $context = null;
    private static $reload;

    public static function render($element)
    {
        // backward compatibility with old version
        if(isset($element['options'])) {
            $options = $element['options'];
            unset($element['options']);
            self::context()->setOptions($options);
        }
        if(isset($element['elements'])) $element = $element['elements'];
        return self::context()->render($element);
    }

    public static function context(): Context
    {
        if (!self::$context) {
            self::$context = new Context();
        }
        return self::$context;
    }

    public static function make($type, $definition = [])
    {
        return self::context()->make($type, $definition);
    }

    public static function config($path, $default = null)
    {
        return config($path, $default);
    }

    public static function isIncluded($element_name)
    {
        return static::$includes[$element_name] ?? false;
    }

    public static function include($element_name)
    {
        static::$includes[$element_name] = true;
    }

    public static function addCSS($css, $path = "")
    {
        if(is_array($css)) {
            foreach($css as $asset) self::addCSS($asset, $path);
            return;
        }
        if(is_null($css)) return;
        $css = $path . $css;
        if( !in_array($css, static::$cssList) ) static::$cssList[] = $css;
    }

    public static function addJS($js, $path = "")
    {
        if(is_array($js)) {
            foreach($js as $asset) self::addJS($asset, $path);
            return;
        }
        if(is_null($js)) return;
        $js = $path . $js;
        if( !in_array($js, static::$jsList) ) static::$jsList[] = $js;
    }
    public static function CSS($html = true)
    {
        if($html){
            $html = "";
            foreach(static::$cssList as $asset){
                $html .= '<link href="' . asset($asset) .'" rel="stylesheet" type="text/css">';
            }
            return $html;
        }
        return static::$cssList;
    }

    public static function JS($html = true)
    {
        if($html){
            $html = "";
            foreach(static::$jsList as $asset){
                $html .= '<script src="' . asset($asset) .'"></script>';
            }
            return $html . self::context()->renderView(view('flatform::actions-js'));
        }
        return static::$jsList;
    }

    public static function livewire()
    {
        return FlatformService::config('flatform.livewire.active', false);
    }

    public static function addAssets($assetName)
    {
        if(!self::isIncluded($assetName)){
            self::include($assetName);
            $pathPrefix = 'flatform.assets.'. $assetName;
            $path = self::config($pathPrefix . '.path');
            self::addCSS(self::config($pathPrefix . '.css'), $path);
            self::addJS(self::config($pathPrefix . '.js'), $path);
            $view = self::config($pathPrefix . '.view');
            return $view ? self::context()->renderView(view($view)) : '';
        }
    }

    public static function getReload()
    {
        return self::$reload;
    }

    public static function setReload($relaod)
    {
        self::$reload = $relaod;
    }

}
