<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\Tag;
use App\User;
use App\UserArticleLike;
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
        // Insert a new article
        $article = [
            'title' => $request['title'],
            'category_id'   => $request['category'],
            'user_id'   => Auth::user()->id,
            'content_md'    => $request['editor-markdown-doc'],
            'content_html'  => $request['editor-html-code'],
            'uri'           => $this->uriFormatter($request['uri']),
            'source_from'   => $request['source-from']
        ];

        $newArticle = Article::create($article);

        // Update article_count in Category table
        $category = Category::find($newArticle->category_id);
        $category->article_count += 1;
        $category->save();

        // Update tags
        $tags = explode(',', $request['tags']);
        foreach($tags as $tag) {
            $existedTag = Tag::where('name', $tag)->first();
            if(!$existedTag) {
                // Insert new tag
                $tag = [
                    'name'  => $tag
                ];
                $existedTag = Tag::create($tag);
            }

            // Now tag has already in the table
            // Only need to insert record in article_tag table
            $articleTag = [
                'article_id'    => $newArticle->id,
                'tag_id'        => $existedTag->id
            ];

            DB::table('article_tag')->insert($articleTag);
        }

        return redirect('/article/'.(empty($newArticle->uri) ? $newArticle->id : $newArticle->uri));
    }

    private function uriFormatter($uri) {
        // convert all character to lower case
        // replace all blank to '_'
        return str_replace(' ', '_', strtolower($uri));
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
            if(!$article['deleted_at']) {
                // add one page view count
                $articleResource = Article::find($article['id']);
                $articleResource->view_count = $article['view_count'] + 1;
                $articleResource->save();
            }

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
        $tagIds = DB::table('article_tag')->where('article_id', $id)->lists('tag_id');
        $tags ='';
        foreach($tagIds as $tagId) {
            $tags = $tags.Tag::find($tagId)->name.',';
        }
        $categories = getCategoryTree();

        return view('articles.edit', compact(['article', 'tags', 'categories']));
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
        // Check whether article category changed.
        // If yes, Must to change the article count of category.
        $originalArticleCategoryId = Article::find($id)->category_id;

        if($originalArticleCategoryId != $request['category']) {
            // minus one count of original category
            $category = Category::find($originalArticleCategoryId);
            $category->article_count -= 1;
            $category->save();
        }

        $article = [
            'title' => $request['title'],
            'category_id'   => $request['category'],
            'content_md'    => $request['editor-markdown-doc'],
            'content_html'  => $request['editor-html-code'],
            'source_from'   => $request['source-from'],
            'uri'           => $request['uri']
        ];

        $response = Article::find($id)->update($article);

        if(!$response) {
            // If article is not updated successfully, correct article count of category.
            $category = Category::find($originalArticleCategoryId);
            $category->article_count += 1;
            $category->save();
        }

        if($response && ($originalArticleCategoryId != $request['category'])) {
            // add one count of new category
            $category = Category::find($request['category']);
            $category->article_count += 1;
            $category->save();
        }

        // Update tags
        $updatingTags = explode(',', $request['tags']); // tag name
        $currentTagIds = DB::table('article_tag')->where('article_id', $id)->lists('tag_id'); // tag id、
        $currentTagsLength = count($currentTagIds);
        foreach($updatingTags as $updatingTag) {
            /**
             * IF (updatingTag is existed in Tags table)
             *     IF (relationship of updatingTag and article is existed)
             *         currentTags rewrite record to empty
             *     ELSE
             *         insert new relationship of tag and article
             * ELSE
             *     insert new tag
             *     insert new relationship of tag and article
             *
             * delete deprecated relationships after this loop (not empty in currentTags)
             */

            $existedTag = Tag::where('name', $updatingTag)->first();

            if($existedTag) {
                $existedArticleTag = 1;
                for($i = 0; $i < $currentTagsLength; $i++) {
                    if($existedTag->id == $currentTagIds[$i]) {
                        $currentTagIds[$i] = '';
                        $existedArticleTag = 0;
                        break;
                    }
                }
                if($existedArticleTag) {
                    $articleTag = [
                        'article_id'    => $id,
                        'tag_id'        => $existedTag->id
                    ];

                    DB::table('article_tag')->insert($articleTag);
                }
            }
            else {
                $tag = [
                    'name'  => $updatingTag
                ];
                $existedTag = Tag::create($tag);

                $articleTag = [
                    'article_id'    => $id,
                    'tag_id'        => $existedTag->id
                ];

                DB::table('article_tag')->insert($articleTag);
            }
        }

        foreach($currentTagIds as $currentTagId) {
            if($currentTagId != '') {
                DB::table('article_tag')->where('tag_id', $currentTagId)->delete();
            }
        }
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
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Set wiki property (is_wiki) of article
     *
     * @param Request $request
     * @param $id
     * @return int|null
     */
    public function setWiki(Request $request, $id)
    {
        $article = Article::find($id);
        $article->is_wiki = $article->is_wiki ? 0 : 1;
        $response = $article->save();

        if($response) {
            return 1;
        }
        else {
            return 0;
        }
    }

    public function modifyLike(Request $request, $id)
    {
        $likeChange = $request['like'];
        // Check if like request is correct.
        $existedLike = UserArticleLike::where('user_id', Auth::user()->id)->where('article_id', $id)->first();

        if(($likeChange == 1) && (!$existedLike)) {
            // add user_article_like record
            $userArticleLike = [
                'user_id'       => Auth::user()->id,
                'article_id'    => $id
            ];
            $createResponse = DB::table('user_article_likes')->insert($userArticleLike);
            if($createResponse) {
                // add one like count
                $article = Article::find($id);
                $article->like_count += 1;
                $response = $article->save();
            }
        }

        if(($likeChange == -1) && ($existedLike)) {
            // delete user_article_like record
            $deleteResponse = UserArticleLike::where('user_id', Auth::user()->id)->where('article_id', $id)->delete();
            if($deleteResponse) {
                // minus one like count
                $article = Article::find($id);
                $article->like_count -= 1;
                $response = $article->save();
            }
        }

        if ($response) {
            return 1;
        } else {
            return 0;
        }
    }

    public function checkUserArticleLike(Request $request)
    {
        $existedLike = UserArticleLike::where('user_id', $request['user_id'])->where('article_id', $request['article_id'])->first();
        if($existedLike) {
            return 1;
        }
        else {
            return 0;
        }
    }
}
