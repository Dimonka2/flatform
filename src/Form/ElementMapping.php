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
        'widget'        => Components\Widget::class,
        'dropdown'      => Components\Dropdown::class,
        'dd-item'       => Components\DropdownItem::class,
        'datatable'     => Components\Datatable\Datatable::class,
        'dt-details'    => Components\Datatable\DatatableDetails::class,
        'dt-select'     => Components\Datatable\DatatableSelect::class,
        'dt-column'     => Components\Datatable\DTColumn::class,
        'progress'      => Components\Progress::class,
        'alert'         => Components\Alert::class,
        'dropzone'      => Components\Dropzone::class,

        // Navs
        'breadcrumbs'   => Navs\Breadcrumbs::class,
        'tabs'          => Navs\Tabs::class,
        'menu'          => Navs\Menu::class,
        'menu-item'     => Navs\MenuItem::class,

        // links and buttons
        'a'             => Link::class,
        'submit'        => Components\Button::class,
        'button'        => Components\Button::class,

        // basic elements
        'form'          => Elements\Form::class,
        'img'           => Elements\Image::class,
        'col'           => Elements\Column::class,
        'label'         => Elements\Label::class,
        'badge'         => Elements\Badge::class,
        'table'         => Elements\Table::class,

        'div'           => ElementContainer::class,
        'span'          => ElementContainer::class,
        'i'             => ElementContainer::class,
        'b'             => ElementContainer::class,
        'u'             => ElementContainer::class,
        'ul'            => ElementContainer::class,
        'li'            => ElementContainer::class,
        '_text'         => Element::class,
        'option'        => Element::class,

        // blade directives
        'include'       => BladeDirective::class,
        'stack'         => BladeDirective::class,
        'yield'         => BladeDirective::class,
        'extends'       => BladeDirective::class,
        'section'       => BladeDirective::class,
    ];
}
