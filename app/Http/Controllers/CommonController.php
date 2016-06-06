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
//        $categoryArray = json_decode(file_get_contents(public_path()."/data/category.json"), true);
//
//        dd($categoryArray);

        return view('index');
    }
}
