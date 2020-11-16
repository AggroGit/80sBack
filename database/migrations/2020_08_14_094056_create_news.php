<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("subtitle")
                  ->nullable();
            $table->string("team")
                  ->default(" ");
            $table->timestamp('publishAt')
                  ->useCurrent();
            //
            $table->integer('association_id')
                  ->nullable()
                  ->references('id')
                  ->on('associations')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            //
            $table->integer('image_id')
                  ->nullable()
                  ->references('id')
                  ->on('images')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->text("text");
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
        Schema::dropIfExists('news');
    }
}
