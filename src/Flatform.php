<?php
namespace dimonka2\flatform;

use laravelcollective\html\Form;
use dimonka2\flatform\Form\Context;
class Flatform
{
    // partial view locations
    private const checkbox_group_class = 'custom-control custom-checkbox mb-3';

    private static $cssList = [];
    private static $jsList = [];
    private static $includes = [];

    public static function render(array $element)
    {
        // dd($element);
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


    public static function renderButton($options)
    {
        // debug($options);
        if(isset($options['type'])) {
            switch ($options['type']) {
                case 'button':
                    if(isset($options['post'])) {
                        return static::renderPostForm($options);
                    } else {
                        $text = "";
                        if(isset($options['title'])) {
                            $text = $options['title'];
                            unset($options['title']);
                        }
                        return static::renderElement('button', $options, $text);
                    }
                    break;
                case 'submit':
                    $text = "";
                    if(isset($options['title'])) {
                        $text = $options['title'];
                        unset($options['title']);
                    }
                    return static::renderElement('button', $options, $text);
                case 'checkbox':
                    $check_config = config('flatform.form.checkbox');
                    $html = '<div class="form-group"><div class="' . static::checkbox_group_class .'">';
                    $id = $options['id'] ?? ('cb-' . $options['name'] ?? '0');

                    $settings = [];
                    $settings['id'] = $id;
                    if(isset($options['class'])) $settings['class'] = $options['class'];
                    $html .= Form::checkbox($options['name'] ?? null, $options['value'] ?? null,
                        null, $settings);
                    if(isset($options['label']) ) {
                        $html .= '<label class="form-check-label custom-control-label" for="' . $id .
                            '">'. $options['label'] . '</label>';
                    }
                    $html .= '</div></div>';
                    return $html;
                default:
                    if(isset($options['post'])) {
                        return static::renderPostForm($options);
                    } else {
                        $text = "";
                        if(isset($options['title'])) {
                            $text = $options['title'];
                            unset($options['title']);
                        }
                        return static::renderElement('a', $options, $text);
                    }
                break;
            }
        }
    }
}
