# flatform
Form helper for Laravel and laravelcollective/html

This is just an idea. Not even an alpha version. 

## Features

- **Separate control rendering from styles.** Write only an interface definition and the styling will be applied based on selected templates.

- **Possibility to switch styles via config.** It is possible to declare several styles and switch between them. Option to switch styles at runtime is coming soon.

- **Write less code.** Using this apprach you define control styles once and focus only on interface declaration.


## Install

composer require dimonka2/flatform

Publish provider:

$ php artisan vendor:publish --provider="dimonka2\flatform\FlatformServiceProvider"

## Configure

Coming soon

## Using in the blade

Following text creates a text input with label
```
@form([
    ['type' => 'row', 'items' => [
        ['type' => 'text', 'label' => 'First name', 'name' => 'first_name',],
        ['type' => 'text', 'label' => 'Last name', 'name' => 'last_name',],
    ]]                                
])
```

Depending on styles it will produce something like:

```
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
