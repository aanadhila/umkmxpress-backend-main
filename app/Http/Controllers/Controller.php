<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function parsingPhonenumber($phonenumber)
    {
        if ($phonenumber[0] == "0") {
            $phonenumber = substr($phonenumber, 1);
        }

        if ($phonenumber[0] == "8") {
            $phonenumber = "62" . $phonenumber;
        }

        return $phonenumber;
    }
}
