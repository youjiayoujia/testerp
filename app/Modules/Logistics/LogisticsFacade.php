<?php
namespace App\Modules\Logistics;

use Illuminate\Support\Facades\Facade;

class LogisticsFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'logistics';
    }

}