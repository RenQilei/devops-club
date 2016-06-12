<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * It will response a page with a well-decorated list.
     * It is no authority required.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = getCategoryTree();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rootCategories = Category::where('parent_category', 0)->get()->toArray();

        return view('categories.create', compact('rootCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $newCategory = Category::create($request->except('_token'));

        return redirect('/category/'.$newCategory->slug);
    }

    /**
     * Display the specified resource.
     * This will response a page of list of articles under specific category id.
     * It is no authority required.
     *
     * @param  mixed  $category
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        // Retrieve info of this specific category.
        if(is_numeric($category)) {
            // $category is category id
            $categoryResource = Category::find($category);
            if($categoryResource == null) {
                return abort(404);
            }
            $categoryId = $category;
        }
        else {
            // $category is category slug
            $categoryResource = Category::where('slug', $category)->first();
            if($categoryResource == null) {
                return abort(404);
            }
            $categoryId = $categoryResource->id;
        }

        // Retrieve article list of this specific category.
        if($categoryResource->article_count > 0) {
            $articles = Article::where('category_id', $categoryId)->get()->toArray();
        }
        else {
            $articles = array();
        }

        // redefine $categoryResource to $category
        $category = $categoryResource->toArray();

        return view('categories.show', compact('category', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
