<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('article_topic', function(Blueprint $table) {
            $table->integer('article_id')->unsigned();
            $table->integer('topic_id')->unsigned();

            $table->foreign('article_id')->references('id')->on('articles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('topics')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['article_id', 'topic_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('article_topic');
        Schema::drop('topics');
    }
}
