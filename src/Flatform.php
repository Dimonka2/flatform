<?php

namespace dimonka2\flatform;

use Illuminate\Support\Facades\Facade;


class Flatform extends Facade
{
    /**
    * Get the registered name of the component.
    *
    * @return string
    */
   protected static function getFacadeAccessor() {
     return 'flatform'; 
   }
}