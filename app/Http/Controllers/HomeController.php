<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole(['Super Admin', 'Admin'])) {
            return to_route('dashboard.index');
        } else {
            return view('home');
        }
    }
}
