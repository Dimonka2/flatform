<?php

namespace dimonka2\flatform\Helpers;

use dimonka2\flatform\Form\Contracts\IContainer;
use dimonka2\flatform\Form\Contracts\IDataProvider;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\ElementContainer;

// string format could be:
    // * "Text"
    // * div span b i u|class
    // * _text $field\modifier
    // * date "format"$field\modifier
    // * money "format"$field\modifier
    // * state "format"$field
    // * bool $field|class( [true element] )( [false element] )
    // * link "route"$field|class
    // * callback "name"
    // inputs with ajax feedback:
    // * checkbox $field|class
    // * select (options)$field|class
    // * text $field|class
    // * textarea $field|class
    // * number $field|class
    // * button "action"|class
    // Parallel elements [element] / [element]
    // Subelements > ( [element] / [element] )

class ColumnFormat
{
    private const splitByDelimiter = '/\s*\/\s*(?![^(]*\))/';

    private static function makeFromString($format, ?IDataProvider $provider, ?IContainer $container = null): ?IElement
    {
        if(!$container) $conatiner = new ElementContainer([], $provider->getContext());
        $items = preg_split(self::splitByDelimiter, $format);
        dump($items);
        return $conatiner;
    }

    public static function make($format, ?IDataProvider $provider): ?IElement
    {
        if(is_string($format) || is_array($format)){
            $context = $provider->getContext();
            $context->setDataProvider($provider);
            if(is_string($format)){
                $element = self::makeFromString($format, $provider)->setParent($provider);
            } else {
                $element = $context->createElement($format);
            }
            $element->setParent($provider);
            $context->setDataProvider(null);
            return $element;
        }
        if(is_object($format)){
            $element->setParent($provider);
        }

        return null;
    }
}
