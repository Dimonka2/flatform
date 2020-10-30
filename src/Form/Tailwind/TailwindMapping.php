<?php

namespace dimonka2\flatform\Form\Tailwind;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\BladeDirective;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Form;

class TailwindMapping
{
    public const bindings = [
        'a'             => Navs\Link::class,
        'link'          => Navs\Link::class,
        'button'        => Navs\Button::class,

        'hidden'        => Inputs\Hidden::class,
        'text'          => Inputs\Input::class,
        'textarea'      => Inputs\Textarea::class,
        'number'        => Inputs\Input::class,
        'select'        => Inputs\Select::class,
        'checkbox'      => Inputs\Checkbox::class,
        'radio'         => Inputs\Radio::class,
        'password'      => Inputs\Password::class,
        'date'          => Inputs\Date::class,
        'file'          => Inputs\File::class,


        'grid'          => Elements\Grid::class,
        'row'           => Elements\Grid::class,
        'col'           => Elements\Column::class,
        'badge'         => Elements\Badge::class,

        'form'          => Form::class,

        // basic Elements
        'div'           => ElementContainer::class,
        'span'          => ElementContainer::class,
        'i'             => ElementContainer::class,
        'b'             => ElementContainer::class,
        'u'             => ElementContainer::class,
        'ul'            => ElementContainer::class,
        'li'            => ElementContainer::class,
        'h1'            => ElementContainer::class,
        'h2'            => ElementContainer::class,
        'h3'            => ElementContainer::class,
        'h4'            => ElementContainer::class,
        'h5'            => ElementContainer::class,
        'h6'            => ElementContainer::class,
        '_text'         => Element::class,
        'option'        => Element::class,

        // blade directives
        'include'       => BladeDirective::class,
        'stack'         => BladeDirective::class,
        'push'          => BladeDirective::class,
        'yield'         => BladeDirective::class,
        'extends'       => BladeDirective::class,
        'section'       => BladeDirective::class,
        'livewire'      => BladeDirective::class,
        
    ];
}
