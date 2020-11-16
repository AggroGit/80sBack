<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sta_notifications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('send_at')->nullable();
            $table->boolean('sended')->default(false);
            $table->string("for")->nullable();
            $table->string("type")->nullable();
            $table->string("title")->nullable();
            $table->text("message")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sta_notifications');
    }
}
