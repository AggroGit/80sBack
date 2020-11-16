<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScratch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scratch', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('points')->default(0);
            $table->integer('of_value')->default(0);
        });

        Schema::create('users_scratch', function (Blueprint $table) {
          $table->id();
          $table->timestamps();
          $table->integer('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->boolean('used')->default(false);
          $table->boolean('available')->default(false);
          //
          $table->integer('scratch_id')
                ->references('id')
                ->on('scratch')
                ->onDelete('cascade')
                ->onUpdate('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scratch');
        Schema::dropIfExists('users_scratch');
    }
}
