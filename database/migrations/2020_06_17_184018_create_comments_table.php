<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->comment('用户id');
            $table->integer('article_id', false, true)->comment('文章ID');
            $table->string('content', 500)->comment('内容');
            $table->foreign('article_id')->references('id')->on('articles');
            $table->timestamps();
            $table->softDeletes(); // 软删除
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
