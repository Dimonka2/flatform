<?php
namespace dimonka2\flatform;

use laravelcollective\html\Form;
use dimonka2\flatform\Form\Context;
class FlatformService
{
    // partial view locations

    private static $cssList = [];
    private static $jsList = [];
    private static $includes = [];

    public static function render(array $element)
    {
        // dd($element);
        // backward compatibility with old version
        if(isset($element['elements'])) $element = $element['elements'];
        return (new Context($element))->render();
    }

    public static function isIncluded($element_name)
    {
        return in_array($element_name, static::$includes);
    }

    public static function include($element_name)
    {
        if( !static::isIncluded($element_name) ) static::$includes[] = $element_name;
    }

    public static function addCSS($css)
    {
        if( !in_array($css, static::$cssList) ) static::$cssList[] = $css;
    }

    public static function addJS($js)
    {
        if( !in_array($js, static::$jsList) ) static::$jsList[] = $js;
    }
    public static function CSS()
    {
        return static::$cssList;
    }

    public static function JS()
    {
        return static::$jsList;
    }

}
