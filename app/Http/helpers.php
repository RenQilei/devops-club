<?php

use App\Article;
use App\Category;
use App\Tag;
use App\User;
use Illuminate\Support\Facades\DB;

/**
 * Generate tree of all categories, and return as an array.
 *
 * @return array
 */
function getCategoryTree() {
    $categories = array();
    $rootCategories = Category::where('parent_category', 0)->get()->toArray();
    foreach($rootCategories as $rootCategory) {
        $childCategories = Category::where('parent_category', $rootCategory['id'])->get()->toArray();
        $rootCategory['children'] = $childCategories;
        array_push($categories, $rootCategory);
    }

    return $categories;
}

/**
 * Retrieve all children categories by parent category id passing in, and return back in array.
 *
 * @param $categoryId
 * @return array
 */
function getChildrenCategoryId($categoryId) {

    return DB::table('categories')->where('parent_category', $categoryId)->lists('id');
}

/**
 * Get articles that are not deleted, and return as an array.
 *
 * @return array
 */
function getArticles() {
    $articles = Article::where('deleted_at', null)->orderBy('created_at', 'desc')->get()->toArray();
    for($i = 0; $i < count($articles); $i++) {
        $articles[$i] = refineArticle($articles[$i]['id']);
    }

    return $articles;
}

function getHotArticles() {
    $articles = Article::where('deleted_at', null)->orderBy('view_count', 'desc')->get()->toArray();
    for($i = 0; $i < count($articles); $i++) {
        $articles[$i] = refineArticle($articles[$i]['id']);
    }

    return $articles;
}

function getArticlesByUserId($id) {
    $articles = Article::where('deleted_at', null)->where('user_id', $id)->orderBy('created_at', 'desc')->get()->toArray();
    for($i = 0; $i < count($articles); $i++) {
        $articles[$i] = refineArticle($articles[$i]['id']);
    }

    return $articles;
}

function getArticlesByCategoryId($id) {

    // get children categories if existed, otherwise, is null
    $categories = getChildrenCategoryId($id);
    // push itself into category array
    array_push($categories, $id);

    $articles = Article::whereIn('category_id', $categories)->orderBy('created_at', 'desc')->get()->toArray();
    for($i = 0; $i < count($articles); $i++) {
        $articles[$i] = refineArticle($articles[$i]['id']);
    }

    return $articles;
}

function getArticleInTrashByUserId($id) {
    // articles is an array, but items are objects
    $articles = DB::table('articles')->whereNotNull('deleted_at')->where('user_id', $id)->orderBy('created_at', 'desc')->get();
    for($i = 0; $i < count($articles); $i++) {
        $articles[$i] = refineArticle($articles[$i]->id);
    }

    return $articles;
}

/**
 * Get article id/uri as a parameter and refine article info, including category, user, abstract and date.
 *
 * @param mixed $article
 * @return array
 */
function refineArticle($article) {
    // Retrieve original article resource
    if(is_numeric($article)) {
        // $article is article id
        $articleResource = DB::table('articles')->where('id', $article)->first();
    }
    else {
        // $article is article uri
        $articleResource = DB::table('articles')->where('uri', $article)->first();
    }

    // convert stdClass to Array
    $article = objectToArray($articleResource);

    if($article) {
        // category info
        $category = Category::find($article['category_id']);
        $article['category_info'] = $category->toArray();
        if($article['category_info']['parent_category']) {
            $article['category_info']['parent_category_info'] = Category::find($article['category_info']['parent_category'])->toArray();
        }
        // user info
        $user = User::find($article['user_id']);
        $article['user_info'] = $user->toArray();
        // abstract
        $article['abstract'] = mb_substr(strip_tags(str_replace(array("\r\n", "\r", "\n"), "", $article['content_html'])),0,100,'utf-8').
            '...<a href="'.refineArticleUrl($article).'">[阅读全文]</a>';
        // date refine
        $article['date'] = substr($article['created_at'], 0, 10);
        // tag info
        $tagIds = DB::table('article_tag')->where('article_id', $article['id'])->lists('tag_id');
        $tags= [];
        foreach($tagIds as $tagId) {
            $tag= [
                'id'    => $tagId,
                'name'  => Tag::find($tagId)->name
            ];
            array_push($tags, $tag);
        }
        $article['tags'] = $tags;
        // meta keywords
        $article['meta_keywords'] = '';
        $tagAmount = count($article['tags']);
        foreach ($article['tags'] as $key => $tag) {
            if ($key < $tagAmount - 1) {
                // 除最后一个以外的 tag 后添加 ','
                $article['meta_keywords'] .= $tag['name'].',';
            }
            else {
                $article['meta_keywords'] .= $tag['name'];
            }
        }
        // meta description -- abstract of the content
        $article['meta_description'] = mb_substr(strip_tags(str_replace(array("\r\n", "\r", "\n"), "", $article['content_html'])),0,100,'utf-8').'...';
    }

    return $article;
}

function refineArticleUrl($article) {
    return url('/article/'.(empty($article['uri']) ? $article['id'] : $article['uri']));
}

/**
 * Convert stdClass to Array
 *
 * @param $array
 * @return array
 */
function objectToArray($array)
{
    if(is_object($array))
    {
        $array = (array)$array;
    }
    if(is_array($array))
    {
        foreach($array as $key=>$value)
        {
            $array[$key] = objectToArray($value);
        }
    }
    return $array;
}