<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLinesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('invoice_lines', function ( Blueprint $table ) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('invoice_id');
            $table->uuid('subscription_id')->nullable();
            $table->uuid('plan_id')->nullable();
            $table->uuid('charge_id')->nullable();

            $table->string('currency')->default(config('subscriptions.currency'));
            $table->text('description')->nullable();

            $table->integer('amount')->default(0);

            $table->integer('quantity')->default(0);

            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();

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
        Schema::dropIfExists('invoice_lines');
    }
}
