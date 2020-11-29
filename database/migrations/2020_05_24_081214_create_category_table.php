<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('category', function (Blueprint $table) {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('pid', false,true);
            $table->string('cate_title', 50);
            $table->string('seo_title', 20)->default('');
            $table->string('seo_name', 50)->default('');
            $table->string('seo_desc', 100)->default('');
            $table->tinyInteger('top', false,true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('category', function (Blueprint $table) {
        //     //
        // });

        Schema::dropIfExists('category');

    }
}
