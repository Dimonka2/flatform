<?php
namespace dimonka2\flatform\Traits;

trait LivewireVersion
{
    public static function isLivewireV1()
    {
        return !class_exists('Livewire\LifecycleManager');
    }
}
