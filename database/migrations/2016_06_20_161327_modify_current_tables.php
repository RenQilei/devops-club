<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCurrentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Modify table: articles
         */
        Schema::table('articles', function ($table) {
            // delete topic_id
            $table->dropColumn('topic_id');
            // update column category_id attribute as unsigned
            $table->integer('category_id')->unsigned()->change();
            // add foreign key of category_id to categories
            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')->onDelete('cascade');
            // update column user_id attribute as unsigned
            $table->integer('user_id')->unsigned()->change();
            // add foreign key of user_id to users
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        /**
         * Modify table: user_article_likes
         */
        Schema::table('user_article_likes', function ($table) {
            // update column user_id attribute as unsigned
            $table->integer('user_id')->unsigned()->change();
            // add foreign keys of user_id to users
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            // update column article_id attribute as unsigned
            $table->integer('article_id')->unsigned()->change();
            // add foreign keys of article_id to articles
            $table->foreign('article_id')->references('id')->on('articles')
                ->onUpdate('cascade')->onDelete('cascade');
            // add primary keys of user_id and article_id
            $table->primary(['user_id', 'article_id']);
        });
        /**
         * Modify table: categories
         */
        Schema::table('categories', function ($table) {
            $table->integer('weight')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * Modify table: articles
         */
        Schema::table('articles', function ($table) {
            // add topic_id
            $table->integer('topic_id')->default(0);
            // delete foreign keys of category_id and user_id
            $table->dropForeign(['category_id', 'user_id']);
        });
        /**
         * Modify table: user_article_likes
         */
        Schema::table('user_article_likes', function ($table) {
            // delete primary key of user_id and article_id
            $table->dropPrimary(['user_id', 'article_id']);
            // delete foreign keys of user_id and article_id
            $table->dropForeign(['user_id', 'article_id']);
        });
        /**
         * Modify table: categories
         */
        Schema::table('categories', function ($table) {
            $table->dropColumn('weight');
        });
    }
}
