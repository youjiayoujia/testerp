<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BarcodeGen extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'barcodeGen';
    }

}