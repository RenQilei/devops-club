<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\User;
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
        $categories = $this->getCategoryTree();

        return view('article.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArticleRequest  $request
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
            'uri'           => str_replace(' ', '_', $request['uri']),
            'source_from'   => $request['source-from']
        ];

        $newArticle = Article::create($article);

        return redirect('/article/'.(empty($newArticle->uri) ? $newArticle->id : $newArticle->uri));
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $article
     * @return \Illuminate\Http\Response
     */
    public function show($article)
    {
        if(is_numeric($article)) {
            // $article is article id
            $articleResource = Article::find($article);
        }
        else {
            // $article is article uri
            $articleResource = Article::where('uri', $article)->first();
        }

        // $article is array of article info or null
        $article = $articleResource ? $articleResource->toArray() : $articleResource;
        if($article) {
            $article['author'] = User::find($article['user_id'])->name;

            // add one page view count
            $articleResource->view_count = $article['view_count'] + 1;
            $articleResource->save();

            return view('article.show', compact('article'));
        }
        else {
            // article is not existed, return 404 page as a response.
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id)->toArray();
        $categories = $this->getCategoryTree();

        return view('article.edit', compact(['article', 'categories']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ArticleRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $id)
    {
        $article = [
            'title' => $request['title'],
            'category_id'   => $request['category'],
            'content_md'    => $request['editor-markdown-doc'],
            'content_html'  => $request['editor-html-code'],
            'source_from'   => $request['source-from']
        ];

        Article::find($id)->update($article);

        return redirect('/article/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Article::destroy($id);
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

    /**
     * Set essential property (is_essential) of article
     *
     * @param Request $request
     * @param $id
     * @return int|null
     */
    public function setEssential(Request $request, $id)
    {
        $article = Article::find($id);
        $article->is_essential = $article->is_essential ? 0 : 1;
        $response = $article->save();

        if ($response) {
            return $article->is_essential;
        } else {
            return null;
        }
    }

    /**
     * Set wiki property (is_wiki) of article
     *
     * @param Request $request
     * @param $id
     * @return int|null
     */
    public function setWiki(Request $request, $id) {
        $article = Article::find($id);
        $article->is_wiki = $article->is_wiki ? 0 : 1;
        $response = $article->save();

        if($response) {
            return $article->is_wiki;
        }
        else {
            return null;
        }
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
