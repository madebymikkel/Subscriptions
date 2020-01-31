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
            $table->bigInteger('user_id');
            $table->uuid('plan_id')->nullable();
            $table->uuid('invoice_id')->nullable();
            $table->string('charge_id')->nullable();

            $table->integer('amount')->default(0);
            $table->integer('amount_refunded')->default(0);

            $table->integer('attempted_count')->default(0);

            $table->dateTime('paid')->nullable();
            $table->dateTime('refunded')->nullable();
            $table->dateTime('attempted')->nullable();

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
        Schema::dropIfExists('charges');
    }
}
