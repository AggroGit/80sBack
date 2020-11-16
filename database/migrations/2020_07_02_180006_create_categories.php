<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")
                  ->nullable();
            $table->integer('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->nullable();
            $table->integer('image_id')
                  ->nullable()
                  ->references('id')
                  ->on('images')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
        });
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')
                  ->nullable()
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string("language");
            $table->text('content')->nullable();
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
        Schema::dropIfExists('categories');
        Schema::dropIfExists('translations');
    }
}
