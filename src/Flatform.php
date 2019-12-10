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

    private static function renderPostForm($options)
    {
        $formOptions = [];
        if(isset($options['form_class'])) {
            $formOptions['class'] = $options['form_class'];
            unset($options['form_class']);
        }

        $formOptions['action'] = $options['href'];
        $formOptions['method'] = 'POST';
        if(isset($options['id'])) $formOptions['id'] = $options['id'];
        $button = [];
        $text = $options['title'] ?? null;
        if(isset($options['class'])) $button['class'] = $options['class'];
        // debug($button);
        $post = $options['post'];
        $_moreText = '';
        if(is_array($post)) {
            if (isset($post['method'])) {
                $_post = $post['method'];
                unset($post['method']);
            } else {
                $_post = 'POST';
            }
            foreach ($post as $key => $value) {
                $_moreText .= Form::hidden($key, $value);
            }
            $post = $_post;
        }

        return static::renderElement('form', $formOptions, csrf_field() . method_field($post) . $_moreText .
            Form::submit($text, $button) );
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
