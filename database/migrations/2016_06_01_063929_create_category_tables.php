<?php

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCategoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->integer('parent_category')->default(0);
            $table->text('description')->nullable();
            $table->integer('article_count')->unsigned()->default(0);
            $table->timestamps();
        });

        $this->categoryInitialisation();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }

    /**
     * Fill up initial categories.
     *
     * @return void
     */

    private function categoryInitialisation()
    {
        // 本地测试时使用，部署环境可忽略。
        if(file_exists(public_path()."/data/category.json")) {
            $categoryArray = json_decode(file_get_contents(public_path()."/data/category.json"), true);

            foreach($categoryArray as $categoryItem) {
                // Insert root category.
                $category = [
                    'name'          => $categoryItem['name'],
                    'slug'          => $categoryItem['slug'],
                    'description'   => $categoryItem['description'],
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ];

                $categoryId = DB::table('categories')->insertGetId($category);

                // If having children, loop to insert child category.
                // NOTICE: parent category is not default.
                if(!empty($categoryItem['children'])) {
                    foreach($categoryItem['children'] as $childCategoryItem) {
                        // Insert child category.
                        $category = [
                            'name'              => $childCategoryItem['name'],
                            'slug'              => $childCategoryItem['slug'],
                            'parent_category'   => $categoryId,
                            'description'       => $childCategoryItem['description'],
                            'created_at'    => Carbon::now(),
                            'updated_at'    => Carbon::now()
                        ];

                        DB::table('categories')->insert($category);
                    }
                }
            }
        }
    }
}
