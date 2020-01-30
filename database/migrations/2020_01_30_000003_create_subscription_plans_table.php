<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();

            $table->string('title');
            $table->text('description')->nullable();

            $table->integer('amount')->default(0);
            $table->integer('interval')->default(1);
            $table->integer('trial_period_days');

            $table->boolean('active')->default(false);


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
        Schema::dropIfExists('subscription_plans');
    }
}
