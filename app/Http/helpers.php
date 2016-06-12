<?php

use App\Article;
use App\Category;
use App\User;

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

function getUserArticles($id) {
    $articles = Article::where('deleted_at', null)->where('user_id', $id)->orderBy('created_at', 'desc')->get()->toArray();
    for($i = 0; $i < count($articles); $i++) {
        $articles[$i] = refineArticle($articles[$i]['id']);
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
        $articleResource = Article::find($article);
    }
    else {
        // $article is article uri
        $articleResource = Article::where('uri', $article)->first();
    }

    // $article is array of article info or null
    $article = $articleResource ? $articleResource->toArray() : $articleResource;
    if($article) {
        $category = Category::find($article['category_id']);
        $user = User::find($article['user_id']);
        $article['category_info'] = $category->toArray();
        $article['user_info'] = $user->toArray();
        $article['abstract'] = mb_substr(strip_tags($article['content_html']),0,100,'utf-8').
            '...<a href="'.refineArticleUrl($article).'">[阅读全文]</a>';
        $article['date'] = substr($article['created_at'], 0, 10);
    }

    return $article;
}

function refineArticleUrl($article) {
    return url('/article/'.(empty($article['uri']) ? $article['id'] : $article['uri']));
}