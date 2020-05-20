<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerDriversController extends Controller
{
    public function index()
    {
        return view('manager.drivers');
    }
}
