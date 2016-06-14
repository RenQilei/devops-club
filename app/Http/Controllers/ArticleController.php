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
        $categories = getCategoryTree();

        return view('articles.create', compact('categories'));
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

        // Update article_count in Category table
        $category = Category::find($newArticle->category_id);
        $category->article_count += 1;
        $category->save();

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
        $categories = getCategoryTree();
        $article = refineArticle($article);

        if($article) {
            // add one page view count
            $articleResource = Article::find($article['id']);
            $articleResource->view_count = $article['view_count'] + 1;
            $articleResource->save();

            return view('articles.show', compact('categories', 'article'));
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
        $categories = getCategoryTree();

        return view('articles.edit', compact(['article', 'categories']));
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
            'source_from'   => $request['source-from'],
            'uri'           => $request['uri']
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
}
