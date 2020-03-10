<?php
namespace dimonka2\flatform\Actions;

use dimonka2\flatform\Http\Requests\ActionRequest;

interface Contract
{
    public function execute();
    public function init(ActionRequest $request);
    public function autorize();
    public static function formatClick($action);
}
