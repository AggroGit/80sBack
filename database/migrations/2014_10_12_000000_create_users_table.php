<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('social_token')->nullable();
            $table->string('social_name')->nullable();
            $table->string('surnames')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('direction')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('birthday')->nullable();
            $table->string('password')->nullable();
            $table->boolean('invited')
                  ->default(false);
            $table->text('device_token')->nullable();
            $table->boolean('payment_bloked')->default(false);
            $table->string('stripe_reciver_id')->nullable();
            $table->rememberToken();
            $table->boolean('birthday_changed')->default(false);
            $table->string('type')
                  ->nullable()
                  ->default('client');
            $table->text('temporal_token')
                  ->nullable();
            $table->decimal('longitude', 10, 7)->default( 2.169914);
            $table->decimal('latitude', 10, 7)->default(41.386907);
            $table->integer('image_id')
                  ->references('id')
                  ->on('images')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->nullable();
            $table->timestamps();
            // sta perpetua puntos
            $table->integer('staPerpetuaPoints')
                  ->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        // Schema::dropIfExists('business');
    }
}
