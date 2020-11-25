<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('user_id')->nullable();
            $table->decimal('longitude', 10, 7)->default( 2.169914);;
            $table->decimal('latitude', 10, 7)->default(41.386907);
            $table->string('direction')->nullable();
            $table->text('description')->nullable();
            $table->text('link')->nullable();
            $table->boolean('reserve')->default(false);
            //
            $table->boolean('verified')->default(false);
            $table->integer('association_id')
                  ->references('id')
                  ->on('associations')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->nullable();

            $table->timestamps();
        });
        // horario de la tienda
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->time('open_from');
            $table->time('open_to');
            // the string day is l,m,x,j,v,s,d
            $table->string('day')->nullable();
            $table->integer('business_id')
                  ->references('id')
                  ->on('business')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->default(1);
            $table->timestamps();
        });
        // imagenes de la tienda, puede haber más de una
        Schema::create('business_images', function (Blueprint $table) {
          $table->integer('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->integer('business_id')
                ->references('id')
                ->on('business')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->timestamps();
        });
        // secciones de la tienda. Ejemplo. Bar Juan ->tapas, embutidos, para llevar
        Schema::create('sections', function (Blueprint $table) {
          $table->id();
          $table->string('name')->nullable();
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
        Schema::dropIfExists('business');
        Schema::dropIfExists('schedule');
        Schema::dropIfExists('business_images');
        Schema::dropIfExists('sections');
        // Schema::dropIfExists('business_sections');
    }
}
