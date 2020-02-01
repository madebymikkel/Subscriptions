<?php

namespace MadeByMikkel\Subscriptions\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MadeByMikkel\Subscriptions\Models\Subscription;

class CancelSubscriptions implements ShouldQueue {
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
        try {
            Subscription::cancelling()->chunk(500, function ( $subscriptions ) {
                foreach ( $subscriptions as $subscription ) {
                    if (!$subscription->delete()) {
                        echo "error.";
                    }
                }
            });
        } catch ( \Exception $e ) {
            echo $e;
        }
    }
}
