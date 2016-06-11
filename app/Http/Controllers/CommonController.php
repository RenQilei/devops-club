<?php

namespace App\Http\Controllers;

use App\Category;
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
        $categories = $this->getCategoryTree();

        return view('index', compact('categories'));
    }

    /**
     * Generate tree of all categories, and return as an array.
     *
     * @return array
     */
    private function getCategoryTree() {
        $categories = array();
        $rootCategories = Category::where('parent_category', 0)->get()->toArray();
        foreach($rootCategories as $rootCategory) {
            $childCategories = Category::where('parent_category', $rootCategory['id'])->get()->toArray();
            $rootCategory['children'] = $childCategories;
            array_push($categories, $rootCategory);
        }

        return $categories;
    }
}
