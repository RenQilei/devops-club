<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = array();
        $rootCategories = Category::where('parent_category', 0)->get()->toArray();
        foreach($rootCategories as $rootCategory) {
            $childCategories = Category::where('parent_category', $rootCategory['id'])->get()->toArray();
            $rootCategory['children'] = $childCategories;
            array_push($categories, $rootCategory);
        }

        return view('article.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $article = [
            'title' => $request['title'],
            'category_id'   => $request['category'],
            'user_id'   => Auth::user()->id,
            'content_md'    => $request['editor-markdown-doc'],
            'content_html'  => $request['editor-html-code'],
            'source_from'   => $request['source-from']
        ];

        $articleId = DB::table('articles')->insertGetId($article);

        return redirect('/article/'.$articleId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);

        return view('article.show', compact('article'));
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

    /**
     * Handle the request of uploading images from editor.
     *
     * @param Request $request
     * @return string
     */
    public function uploadImage(Request $request)
    {
        if($request->hasFile('editormd-image-file')) {
            $image = $request->file('editormd-image-file');
            if($image->isValid()) {
                $path = 'static/images/'.$request->user()->id;
                $name = md5(uniqid().$image->getClientOriginalName())."_".str_replace(['-', ':', ' '], "", Carbon::now()).".".$image->getClientOriginalExtension();
                $image->move($path, $name);
                $url = asset($path.'/'.$name);
            }
            else {
                $message = 'Uploading image Invalid.';
            }
        }
        else {
            $message = 'No image uploaded.';
        }

        $data = array(
            'success'   => isset($message) ? 0 : 1,
            'message'   => isset($message) ? $message : 'Image uploaded successfully.',
            'url'       => isset($url) ? $url : ''
        );

        return json_encode($data);
    }
}
