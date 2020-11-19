<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // cada órden de la app
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default(1);
            $table->double('howmuch')->nulable();
            // en caso de devolución se guardará el stripe id de la devolución
            $table->string('refund_stripe_id')->nullable();
            $table->double('price', 8, 2);
            $table->boolean('is_offer')->default(false);
            $table->string('status')->default('selected');
            $table->string('price_per');
            $table->string('description')->nullable();
            // pay in hand?
            $table->boolean('pay_in_hand')->default(false);
            // user
            $table->integer('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // product
            $table->integer('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // size
            $table->integer('size_id')
                  ->nullable()
                  ->references('id')
                  ->on('sizes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // the purchase
            $table->integer('purchase_id')
                  ->nullable()
                  ->references('id')
                  ->on('purchase')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            // NEW
            // the business
            $table->integer('business_id')
                  ->nullable()
                  ->references('id')
                  ->on('business')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
            // sta perpetua
            $table->integer('discount_id')
                  ->nullable()
                  ->references('id')
                  ->on('discounts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

        });



        // Éste representa una compra de diferentes órdenes
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->double('total_price', 8, 2); // el precio que se cobra al cliente
            $table->string('stripe_payment_id')->nullable(); // el id del pago al cliente
            $table->string('stripe_refound_id')->nullable(); // en caso que se devuelva
            $table->string('status')->default('pending');// pending, coming, delivered
            $table->integer('payment_tries')->default(0);
            $table->timestamp('nextTry')->nullable();
            $table->double('stripe_commisions', 8, 2)->nullable();
            $table->double('merco_commisions', 8, 2)->nullable();
            // solo será true cuando se efectúe las transferencias
            $table->boolean('completed')->default(false);
            $table->boolean('birthday')->default(false);
            $table->boolean('pay_in_hand')->default(false);
            $table->integer('discount_id')
                  ->references('id')
                  ->on('discounts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->nullable();
            // the user
            $table->integer('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            //
            $table->timestamps();

        });


        // Éste representa un pago externo
        Schema::create('pay_outs', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_payout_id')->nullable(); // el id del pago al comerciante
            $table->timestamp('money_send_at')->nullable();// cuando se enviará el dinero
            $table->double('price_sended', 8, 2);           // precio real que se ha enviado al comerciante
            $table->double('comision', 8, 2);               //
            //
            $table->integer('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            //
            $table->integer('purchase_id')
                  ->references('id')
                  ->on('purchase')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            //
            $table->integer('business_id')
                  ->references('id')
                  ->on('business')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            //
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
        //
        Schema::dropIfExists('purchase');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('pay_outs');
    }
}
