<?php
namespace dimonka2\flatform\Actions;

use Illuminate\Support\Fluent;

interface Contract
{
    public function execute();
    public function init(array $params);
    public function autorize();
    public static function formatClick($action);
    public function form();
}
