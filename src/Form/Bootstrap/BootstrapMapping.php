<?php

namespace dimonka2\flatform\Form\Bootstrap;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\BladeDirective;
use dimonka2\flatform\Form\ElementContainer;

class BootstrapMapping
{
    public const bindings = [

        // Inputs
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

        // Components
        'widget'        => Components\Widget::class,
        'modal'         => Components\Modal::class,
        'datatable'     => Components\Datatable\Datatable::class,
        'dt-details'    => Components\Datatable\DatatableDetails::class,
        'dt-select'     => Components\Datatable\DatatableSelect::class,
        'dt-column'     => Components\Datatable\DTColumn::class,
        'dt-filter'     => Components\Datatable\DTFilter::class,
        'progress'      => Components\Progress::class,
        'alert'         => Components\Alert::class,
        'dropzone'      => Components\Dropzone::class,
        'jstree'        => Components\JsTree\Tree::class,
        'xtable'        => Components\Table\Table::class,

        // Navs
        'breadcrumbs'   => Navs\Breadcrumbs::class,
        'tabs'          => Navs\Tabs::class,
        'menu'          => Navs\Menu::class,
        'menu-item'     => Navs\MenuItem::class,
        'dropdown'      => Navs\Dropdown::class,
        'dd-item'       => Navs\DropdownItem::class,

        // links and buttons
        'a'             => Link::class,
        'link'          => Link::class,
        'submit'        => Navs\Button::class,
        'button'        => Navs\Button::class,

        // basic Elements
        'form'          => Elements\Form::class,
        'img'           => Elements\Image::class,
        'col'           => Elements\Column::class,
        'badge'         => Elements\Badge::class,
        'table'         => Elements\Table::class,

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
