<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('invoices', function ( Blueprint $table ) {
            $table->uuid('id')->primary()->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->uuid('charge_id');
            $table->uuid('subscription_id');

            $table->integer('amount_due')->default(0);
            $table->integer('amount_paid')->default(0);
            $table->integer('amount_remaining')->default(0);

            $table->integer('attempt_count')->default(0);

            $table->dateTime('attempted')->nullable();
            $table->dateTime('paid')->nullable();
            $table->dateTime('refunded')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down () {
        Schema::dropIfExists('invoices');
    }
}
