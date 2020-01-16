<?php

namespace dimonka2\flatform\Form;

class ElementMapping
{
    public const bindings = [
        // inputs
        'text'          => Inputs\Text::class,
        'password'      => Inputs\Password::class,
        'number'        => Inputs\Number::class,
        'textarea'      => Inputs\Textarea::class,
        'summernote'    => Inputs\Summernote::class,
        'select'        => Inputs\Select::class,
        'select2'       => Inputs\Select2::class,
        'bselect'       => Inputs\BootstrapSelect::class,
        'file'          => Inputs\File::class,
        'checkbox'      => Inputs\Checkbox::class,
        'radio'         => Inputs\Radio::class,
        'date'          => Inputs\Date::class,
        'hidden'        => Inputs\Hidden::class,

        // components
        'tabs'          => Components\Tabs::class,
        'widget'        => Components\Widget::class,
        'dropdown'      => Components\Dropdown::class,
        'dd-item'       => Components\DropdownItem::class,
        'datatable'     => Components\Datatable::class,
        'breadcrumbs'   => Components\Breadcrumbs::class,
        'progress'      => Components\Progress::class,
        'alert'         => Components\Alert::class,

        // links and buttons
        'a'             => Link::class,
        'submit'        => Components\Button::class,
        'button'        => Components\Button::class,

        'form'          => Form::class,
        'img'           => Elements\Image::class,
        'col'           => Elements\Column::class,
        'label'         => Elements\Label::class,

        'div'           => ElementContainer::class,
        'span'          => ElementContainer::class,
        'i'             => ElementContainer::class,
        'b'             => ElementContainer::class,
        'u'             => ElementContainer::class,
        'ul'            => ElementContainer::class,
        'li'            => ElementContainer::class,
        '_text'         => Element::class,
        '_template'     => Element::class,
        'option'        => Element::class,

        // blade directives
        'include'       => BladeDirective::class,
        'stack'         => BladeDirective::class,
        'yield'         => BladeDirective::class,
        'extends'       => BladeDirective::class,
        'section'       => BladeDirective::class,
    ];
}
