<?php

namespace dimonka2\platform\Form;

use ElementContainer;
use ReflectionClass;

class Context
{
    protected $elements;

    protected $binds = [
        'div' => ElementContainer::class,
        'span' => ElementContainer::class,
    ];

    public function __construct(array $elements = [])
    {
        parent::__construct();
        $elements = new ElementContainer($elements, $this);
    }

    public function create(array $element)
    {
        $def_type = config('platform.form.default-type');
        $type = $element['type'] ?? $def_type;
        $class = isset($this->binds[$type]) ? $this->binds[$type] : $this->binds[$def_type];
        // make class
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs($element);
    }
}
