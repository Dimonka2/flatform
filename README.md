# Laravel Flatform


[![Packagist](https://img.shields.io/packagist/v/dimonka2/flatform.svg)](https://packagist.org/packages/dimonka2/flatform)
[![Packagist](https://img.shields.io/packagist/l/dimonka2/flatform.svg)](https://packagist.org/packages/dimonka2/flatform) 
[![Packagist](https://img.shields.io/packagist/dm/dimonka2/flatform.svg)]()


HTML control rendering helper for Laravel and Livewire

## Features

- **Separate control rendering from control styling.** Write only an interface definition and the styling will be applied based on selected templates.

- **Possibility to switch styles via config.** It is possible to declare several styles and switch between them. Option to switch styles at runtime is coming soon.

- **Write less code.** Using this approach you define control styles once and focus only on interface declaration.

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
* **Blade directives**: stack, include, yield, section, Livewire
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
        ]);

        return $table;
    }
}
```
This component will generate a table with a user list with 4 columns.

### Table component properties and functions
Class: `dimonka2\flatform\Livewire\TableComponent`

| Property | Default | Usage |
| -------- | ------- | ----- |
|`idField`|'id'|ID field that needs to be is equal to key column name field. This field is required to be properly setup if you use **Table Select** or **Table Details** functions|
|`selectAll`|false|Indicates whether user has clicked 'Select all' checkbox|
|`search`|null|Current search string|
|`searchDebounce`|500|Search input bounce time, see Livewire documentation|
|`order`|""|Currently ordered column name. The format could be a name as a string or an array like ['fieldName' => 'DESC] |
|`length`|10| Currently selected number of elements per page|
|`class`||??|
|`expanded`|[]|Array of expanded row IDs|
|`filtered`|[]|Array of filter values|
|`selected`|[]|Array of selected row IDs|
|`info`||  // make it false to exclude info column|
|`table`||Contains the current instance of Table definition|
|`rowsReady`|null|Indicates that table rows are queried and prepared for rendering|
|`scrollUp`|true|Scrolls page up to the table header after paginator click|

Table component has also few functions, that can be called, defined or overridden:
| Function | Defined | Parameters | Usage |
| -------- | ------- | ---------- | ----- |
|`getTable()`|yes||This is the main function that returns Table class that describes the table properties. This function has to return class `dimonka2\flatform\Form\Components\Table\Table`|
|`getQuery()`|no||Enables defining table query as a separate function, it has to return `Builder` class|
|`getSelect()`|no||Enables defining table Select as a separate function|
|`getDetails()`|no||Enables defining table Details as a separate function|
|`getActions()`|no||Enables defining table Actions as a separate function|
|`getFilters()`|no||Enables defining table Filters as a separate function|
|`getView()`|yes|$viewName|By overriding this function you may replace default table views by your own views blades|
|`getLengthOptions()`|yes||Enables to override length option: number of items per page in filter dropdown|



### Table definition properties
Class: `dimonka2\flatform\Form\Components\Table\Table` 

| Property | Default | Usage |
| -------- | ------- | ----- |
|`actions`|null|Define table actions as sub array|
|`columns`|[]|Sub array with table column definitions. See **Table Column properties** section|
|`details`|null|Enables table row details as sub array options. See **Table Details properties** section|
|`filters`|null|Enables table query filters as sub array options. See **Table Filter properties** section|
|`lengthOptions`| [10, 20, 30, 50, 100] |Number of items per page in filter dropdown|
|`evenOddClasses`| ['even', 'odd'] |Array with even/odd classes|
|`query`|null|Laravel Builder query that might contain any kind of joins, whereExists, with or Counts|
|`search`|null|Setting this property to `false` will disable and hide table search functionality|
|`select`|null|Enables and define row select options as sub array options. See **Table Select properties** section|
|`rows`|null|Sub array with table rows definitions. You can define content of rows and columns without setting up `query` property|
|`formatters`|[]|Lookup array for custom column formatters|
|`formatFunction`|null|TD element format closure function|
|`links`|null|Setting this property to `false` will hide pagination links|
|`rowRenderCallback`|null|Callback required for a Livewire table to separate table from rows rendering, currently unused|
|`order`|null|Default ordered column. Can be defined as a column name or as `false` to disable table ordering|

### Table Column properties
| Property | Default | Usage |
| -------- | ------- | ----- |
|`name`|null|Field name, this name is queried from the query via select, also the row data will use field name as a key|
|`title`|null|Column title used in a header. Title might be in Flatform rendering format|
|`search`|false|Setting this property to true will include this column in text search query|
|`sort`|ASC|Setting to false will disable sort by this column, setting column to "DESC" will make DESC as default (first) sort order|
|`system`|false|Setting this property to true means a virtual (calculated) field without adding it to select query statement with disabled sort and search. Useful for calculated fields and menu elements|
|`class`|null|Class will be added to column's TD and TH tag class attribute|
|`hide`|false| `true` denotes that this column will not be rendered|
|`raw`|null|This option might be used when you need a calculated value and it used as DB:raw select statement|
|`noSelect`|null|Setting this to true denotes a special case for some columns are in a select statement and there is no need to add an extra select, like “count()”|
|`as`|see usage|This property specifies field alias and how this column will be mapped to the Model attributes. If this field is undefined for a column fields with table name like `users.name` will have a replacement of dot to '__' e.g.  `users__name`|
|`format`|null|Column format: callable (IColumnFormat), Flatform language definition or container|
|`_format`|null|Quick column format: 'number','link', 'check', 'str'|
|`width`|null|Column width style. Column width will be added to the header style and each TD tag style|

### Table Details properties
Table Details is a pull down row that may contain any additional details connected to the row including even Livewire components. 

| Property | Default | Usage |
| -------- | ------- | ----- |
|`expander`| Flatform expander| You may override default table expander by defining this property. See default expander setup in `TableDetails::default_expander` |
|`callback`|null|Closure function that should return the content of the row details, defined as `function ($row){}`|
|`disabled`|false|Setting this option to true will disable details|
|`title`|null|Detail column title|
|`class`|null|Style classes that will be applied to the expander TD element|
|`trClass`|null|Style classes that will be applied to the details TR element|
|`tdClass`|null|Style classes that will be applied to the details TD element|
|`width`|null|Expander column width style|

Example of table with details:

```php
protected function getTable(): ?Table
    {
        $table = new Table([
            'columns' => [
                // ...
            ],
            'details' => [
                'callback' => function($row) {
                    // get a queried model from the row
                    $model = $row->_item;
                    return 'Model name: <strong>' . $model->name . '</strong>';
                    // or alternatively in Flatform language:
                    return [['span', 'text' => 'Model name: '], ['strong', 'text' => $model->name]];
                }
            ],
        ]);

        return $table;
    }
}
```

### Table Select properties
Table Select is an additional checkbox in most left column that enables to select table rows.
| Property | Default | Usage |
| -------- | ------- | ----- |
|`checkbox`|Flatform checkbox|Definition of the checkbox in flatform format|
|`headerCheckbox`|Flatform checkbox|Definition of the checkbox in header that works as "select all" in flatform format|
|`column`|???||
|`disabled`|false|Setting this option to `true` will disable Table Select|
|`width`|null|Checkbox column width style|
|`selectCallback`|null|Callback function used internally by the TableComponent to determine whether the row is selected|
|`class`|null|Style classes that will be applied to the selected row TR element|

Example of table with Select that will highlight selected rows using class `table-primary`:

```php
protected function getTable(): ?Table
    {
        $table = new Table([
            'columns' => [
                // ...
            ],
            'select' => [
                'class' => 'table-primary',
            ], 
        ]);

        return $table;
    }
}
```

### Table Action properties

Table actions should be defined together with TableSelect as they currently rendered for selected items.

| Property | Default | Usage |
| -------- | ------- | ----- |
|`name`| |Action unique name. This can be ignored. See example.|
|`position`|null|array where action is rendered: `selection`, `dropdown`, `row`, `row-dd`|
|`title`| |action title or label|
|`disabled`|false| Setting this option to `true` will disable this action|
|`callback`||Action callback. This can be ignored. See example.|
|`attributes`||All other elements. Those attributes are populated automatically from the unspecified action properties|

Example of table with Table Select and Actions:

```php
    protected function getTable(): ?Table
        {
            return new Table([
                'columns' => [
                    // ...
                ],
                'select' => [
                ], 
                'actions' => [
                    // in this case we just calling a normal Livewire action for this action
                    ['title' => 'Disable selected users', 'position' => 'dropdown',
                        'icon' => 'fa fa-ban', 'wire:click.prevent' => 'disableUsers', 'href' => '#'],
                ],
            ]);
            
        }
    }
    // handle action
    public function disableUsers()
    {
        // get selected models
        $users = $this->getSelected(true);
        // disable users one by one
        foreach ($users as $user) {
            if($user->id != Auth::user()->id) {
                $user->disabled = $disable;
                $user->update();
            }
        }
        // reload table rows in order to populate changes
        $this->reload();        
    }
```

### Table Filter properties
Filter is a basic Flatform input control that is associated with persistent input and a closure function that can be attached to filter query based on the user input.
| Property | Default | Usage |
| -------- | ------- | ----- |
|`name`|           | Filter name, assigned to input. This name is used to map filter input. Name has to be unique.|
|`title`|          | Filter control title or label|
|`type`|           | Filter type: checkbox, select, text|
|`disabled`|false  | Disables filter|
|`value`|          | Default value|
|`list`|           | Item list for select, might be a closure|
|`filterFunction`| | Filter callback in format: `function($query, $data) {}`|

