# Laravel Flatform


[![Packagist](https://img.shields.io/packagist/v/dimonka2/flatform.svg)](https://packagist.org/packages/dimonka2/flatform)
[![Packagist](https://img.shields.io/packagist/l/dimonka2/flatform.svg)](https://packagist.org/packages/dimonka2/flatform) 
[![Packagist](https://img.shields.io/packagist/dm/dimonka2/flatform.svg)]()


HTML control rendering helper for Laravel and Livewire

## Features

- **Separate control rendering from control styling.** Write only an interface definition and the styling will be applied based on selected templates.

- **Possibility to switch styles via config.** It is possible to declare several styles and switch between them. Option to switch styles at runtime is coming soon.

- **Write less code.** Using this apprach you define control styles once and focus only on interface declaration.

- **Livewire table component.** Unique Livewire table with sorting, search, filters, actions, row select, column formatters and sub details. 

## Install

composer require dimonka2/flatform

Publish provider:

$ php artisan vendor:publish --provider="dimonka2\flatform\FlatformServiceProvider"

## Configure

Coming soon

## Using in the blade

Following text creates text inputs with labels
```php
@form([
    ['row', [
        ['text', 'label' => 'First name', 'name' => 'first_name',],
        ['text', 'label' => 'Last name', 'name' => 'last_name',],
    ]]                                
])
```

Depending on styles the code above will generate something like:

```html
<div class="row">
    <div class="col-6 form-group">
        <label for="first_name-100">First name</label>
        <input id="first_name-100" class="  form-control form-control-alt" name="first_name" type="text">
    </div>
    <div class="col-6 form-group">
        <label for="last_name-101">Last name</label>
        <input id="last_name-101" class="  form-control form-control-alt" name="last_name" type="text">
    </div>
</div>
```

## Included elements, inputs and components
* **Inputs**: text, password, number, textarea, select, file, date, checkbox, radio, hidden, select2, bootstrap select, summernote
* **Components**: alert, breadcrumb, button, dropdown, progress, tabs, widget, datatable, table
* **Trait for datatable**
* **HTML tags**: form, text, div, row (div with class "row"), col (div with class 'col-xx')
* **Blade directives**: stack, include, yield, section, livewire
* **TableComponent**: Livewire table component

## Documentation

Coming soon

## TableComponent

In order to create a Livewire TableComponent you cave to create a class in your Livewire folder that is derived from *TableComponent* class. See following sample code:

```php
namespace App\Http\Livewire\User;

use App\Models\User;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Livewire\TableComponent;
use dimonka2\flatform\Form\Components\Table\Table;

class UserList extends TableComponent
{
protected function getTable(): ?Table
    {
        $table = new Table([
            'class' => 'table table-hover',
            'columns' => [
                ['name' => 'name'       , 'title'   => 'Name'       , 'search',    ],
                ['name' => 'email'      , 'title'   => 'Email'      , 'search',    ],
                ['name' => 'position'   , 'title'   => 'Position'   , 'search',    ],
                ['name' => 'updated_at' , 'title'   => 'Last update', 'sort' => 'Desc'  , '_format' => "date", ],
                ['name' => 'id', 'hide'],
            ],
            'order' => 'updated_at',
            'query' => User::whereNull('disabled_at'),
        ], Flatform::context());

        return $table;
    }
}
```
This component will generate a table with a user list with 4 columns.

### Table properties

* `lengthOptions` - number of itmems per page in filter dropdown. Default: [10, 20, 30, 50, 100]
* `evenOddClasses` - array with even/odd classes. Default: ['even', 'odd'];
* `query` - Laravel Builder query that might contain any kind of joins, whereExists or Counts 
* `search` - setting this property to false will disable search functionality
* `select` - enable and define row select options as sub array options 
* `actions` - define table actions as sub array
* `columns` - sub array with table column definitions
* `rows`  - sub array with table rows definitions. You can define content of rows and columns without setting up `query` property 
* `details` - define table row details as sub array options
* `formatters` - lookup list for column formatters
* `formatFunction` - this is a table td element format function
* `info` - setting this property to false will hide info column
* `links` - setting this property to false will hide pagination links
* `rowRenderCallback` - callback required for a livewire table to separate table from rows rendering

### Table Column properties
* `name`  -  field name, that is queried from the query via select, also the row data will use field name as a key
* `title` - column title used in a header. Title might be in Flatform rendering format.
* `search` - setting this property to false will exclude this column from searching
* `sort` - setting to false will disable sort by this column, setting column to "DESC" will make DESC as default (first) sort order
* `system` - setting this property to true means a virtual (calculated) field with disabled sort and search
* `class`  - this class will be added to column's TD and TH tag classes
* `hide` - true denotes that this column is not displayed
* `raw` - this option oes mean that the select is calculated value and it should be used DB:raw select
* `noSelect` - setting this to true denotes a special case for some columns there is no need to add a select, like "count"
* `as` - this property specifies how this column will be mapped to the Model attributes. If this field is undefined for a column fields with table name like `users.name` will have a replacement of dot to '__' e.g.  `users__name`
* `format` - column format: callable (IColumnFormat), container or template name 
* `width` - column width. Column width will be added to the header style and each TD tag style.
