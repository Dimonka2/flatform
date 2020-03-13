<?php
    use Illuminate\Support\Facades\Route;
    Route::post('ajax/action-run', "ActionController")->name('action');
    Route::post('ajax/action-fields', "ActionController@getForm")->name('action-fields');
