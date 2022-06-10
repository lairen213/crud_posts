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
        $months = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];

        $startTime = new Datetime($datetime);
        return $months[(int)$startTime->format('m') - 1].' '.(int)$startTime->format('d').', '.$startTime->format('Y').' at '.$startTime->format('H') . ':' . $startTime->format('m');
    }
}
