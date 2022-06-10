<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //Method for beautify datetime
    public function beautifyDateTime($datetime){
        $startTime = new Datetime($datetime);
        return $startTime->format('F').' '.(int)$startTime->format('d').', '.$startTime->format('Y').' at '.$startTime->format('H') . ':' . $startTime->format('m');
    }
}
