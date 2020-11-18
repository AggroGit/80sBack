<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                  ->nullable();
            $table->string('description')
                  ->nullable();
            $table->boolean('hidden')->default(false);
            $table->double('price', 8, 2)->nullable();
            $table->double('offer_price', 8, 2)->nullable();
            $table->integer('sales')->nullable()->default(1);
            $table->integer('views')->nullable()->default(1);
            // price per unit, pack_of_units, ml, g, kg, L
            $table->string('price_per')
                  ->default("unit");
            //
            $table->integer('business_id')
                  ->default(1)
                  ->references('id')
                  ->on('business')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            //
            $table->integer('category_id')
                  ->nullable()
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
        });
        // como habra imagenes limitadas pero posibilidad que en un futuro
        // haya mas hacemos las imagenes de producos en tabla aparte
        Schema::create('products_images', function (Blueprint $table) {
          $table->integer('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->integer('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->timestamps();
        });

        // cada producto puede tener infinitas tallas o variaciones
        Schema::create('sizes', function (Blueprint $table) {
          $table->id();
          $table->string('name')->nullable();
          $table->double('price', 8, 2)->nullable();
          $table->double('offer_price', 8, 2)->nullable();
          $table->string('description')->nullable();
          $table->integer('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->timestamps();
        });

        // Un producto puede estar en una o más secciones de la tienda
        Schema::create('product_section', function (Blueprint $table) {
          $table->integer('section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('cascade')
                ->onUpdate('cascade');
          $table->integer('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                // ya de paso el business again
          $table->integer('business_id')
                ->references('id')
                ->on('business')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('products_images');
        Schema::dropIfExists('product_section');
        Schema::dropIfExists('sizes');
    }
}
