<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserArticleLike extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_article_likes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'article_id'];
}
