<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // the chat
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->timestamps(0);
            $table->integer('max')
                  ->nullable()
                  ->default(null);
            $table->string('name')
                  ->nullable();
            $table->boolean('open')
                  ->default(true);
            // tienda de la que se abre chat
            $table->integer('business_id')
                  ->nullable() // TEMPORAL
                  ->references('id')
                  ->on('business')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');


        });

        // user belongs to chat
        Schema::create('chats_users', function (Blueprint $table) {
          $table->integer('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

          $table->integer('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          // remove chat
          $table->boolean('hidden')
                ->nullable()
                ->default(false);
          // block chat
          $table->boolean('blocked')
                ->nullable()
                ->default(false);

        });

        // the message of a chat
        Schema::create('messages', function (Blueprint $table) {
          $table->id();
          $table->integer('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->text('message');
          $table->timestamps(0);

          $table->integer('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->integer('image_id')
                ->nullable()
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->boolean('read')
                ->default(false);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('chats');
        Schema::dropIfExists('chats_users');
        Schema::dropIfExists('messages');
    }
}
