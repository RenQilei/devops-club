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
        // Use array to store category info, in case more than one root and child category
        $categoryResources = array();

        // Retrieve category info, if not existed, throw 404 error.
        if(is_numeric($category)) {
            // $category is category id
            $categoryResource = Category::find($category);
            if($categoryResource == null) {
                return abort(404);
            }
            array_push($categoryResources, $categoryResource);
            $categoryId = $category;
        }
        else {
            // $category is category slug
            $categoryResource = Category::where('slug', $category)->first();
            if($categoryResource == null) {
                return abort(404);
            }
            array_push($categoryResources, $categoryResource);
            $categoryId = $categoryResource->id;
        }

        // Check if it is root category will retrieve child category info
        if($categoryId == 0) {
            $childCategoryResources = Category::where('parent_category', $categoryId)->get();
            array_push($categoryResources, $childCategoryResources);
        }

        // Retrieve article list of this specific category.
        if($categoryResource->article_count > 0) {
            $articles = getArticlesByCategoryId($categoryId);
        }
        else {
            $articles = array();
        }

        // redefine $categoryResource to $category
        $category = $categoryResource->toArray();
        $category['parent_category_info'] = $category['parent_category'] ? Category::find($category['parent_category'])->toArray() : null;

        $categories = getCategoryTree();

        return view('categories.show', compact('categories', 'category', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rootCategories = Category::where('parent_category', 0)->get()->toArray();
        $category = Category::find($id)->toArray();

        return view('categories.edit', compact('rootCategories','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        Category::find($id)->update($request->except('_token'));

        return redirect('/category/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flag = 0; // default: do not allow to delete category
        $destroyedCategory = 0;
        $category = Category::find($id);

        // root category, AND no child category left, AND no article left
        if(($category['parent_category'] == 0) && (!Category::where('parent_category', $id)->get()) && (!$category['article_count'])) {
            $flag = 1;
        }

        // child category, AND no article left
        if(($category['parent_category'] != 0) && (!$category['article_count'])) {
            $flag = 1;
        }

        if($flag) {
            $destroyedCategory = Category::destroy($id);
        }

        return $destroyedCategory;
    }
}
