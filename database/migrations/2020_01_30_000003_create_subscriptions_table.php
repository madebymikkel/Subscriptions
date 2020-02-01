<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('subscriptions', function ( Blueprint $table ) {
            $table->uuid('id')->primary()->unique();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->uuid('plan_id')->index();

            $table->boolean('cancel_at_period_end')->default(false);

            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();

            $table->dateTime('cancelled_at')->nullable();

            $table->dateTime('trial_start')->nullable();
            $table->dateTime('trial_end')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_id')->references('id')->on('subscription_plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down () {
        Schema::dropIfExists('subscriptions');
    }
}
