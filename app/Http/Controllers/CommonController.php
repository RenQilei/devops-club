<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    /**
     * Homepage of DevOps Club -- index page
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('index');
    }
}
