<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('charges', function ( Blueprint $table ) {
            $table->uuid('id')->primary()->unique();

            $table->bigInteger('user_id')->unsigned()->index();
            $table->uuid('plan_id')->nullable()->index();
            $table->uuid('invoice_id')->nullable()->index();
            $table->string('charge_id')->nullable()->index();

            $table->integer('amount')->default(0);
            $table->integer('amount_refunded')->default(0);

            $table->integer('attempted_count')->default(0);

            $table->dateTime('paid')->nullable();
            $table->dateTime('refunded')->nullable();
            $table->dateTime('attempted')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_id')->references('id')->on('subscription_plans');
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down () {
        Schema::dropIfExists('charges');
    }
}
