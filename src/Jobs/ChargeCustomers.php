<?php

namespace MadeByMikkel\Subscriptions\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MadeByMikkel\Subscriptions\Models\Subscription;

class ChargeCustomers implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct () {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle () {
        Subscription::active()->pastDue()->chunk(500, function ( $subscriptions ) {
            foreach ( $subscriptions as $subscription ) {
                $now = Carbon::now();
                if (!$subscription->user->renewSubscription($subscription)) {
                    echo "Something went wrong.";
                }

                echo Carbon::now()->diffInMilliseconds($now) . PHP_EOL;
            }
        });
    }
}
