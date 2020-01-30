<?php

namespace MadeByMikkel\Subscriptions;

use Carbon\Carbon;
use MadeByMikkel\Subscriptions\Models\Subscription;
use MadeByMikkel\Subscriptions\Models\SubscriptionPlan;
use Stripe\Charge as StripeCharge;
use Stripe\Customer;

trait Subscribable {

    /**
     * @param $plan_id
     * @param array $options
     * @return bool|mixed
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function beginSubscription ( $plan_id, array $options = [] ) {

        if ( $this->subscribed() ) {
            return false;
        }

        $subscription_plan = SubscriptionPlan::find($plan_id);

        if ( !$subscription_plan ) {
            return $this->subscription_fails();
        }

        $charge = $this->charge($subscription_plan, $options);

        if ( !$charge ) {
            return $this->subscription_fails();
        }

        return $this->createSubscription($subscription_plan) ?
            $this->subscription_success()
            : $this->subscription_fails();
    }

    /**
     * The subscription failed.
     *
     * @return mixed
     */
    protected function subscription_fails () {
    }

    /**
     * The subscription was created successfully.
     *
     * @return mixed
     */
    protected function subscription_success () {
    }

    /**
     * @param null $subscription_plan
     * @return bool
     */
    private function createSubscription ( $subscription_plan ) {

        $subscription = Subscription::create([
            'user_id'      => $this->id,
            'plan_id'      => $subscription_plan->id,
            'period_start' => Carbon::now(),
            'period_end'   => Carbon::now()->addMonths($subscription_plan->interval),
        ]);

        if ( !$subscription ) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function subscribed () {
        return Subscription::whereUserId($this->id)->first();
    }

    /**
     * @param $subscription_plan
     * @param array $options
     * @return bool
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function charge ( $subscription_plan, array $options = [] ) {

        if ( !$subscription_plan ) {
            return false;
        }

        if ( !array_key_exists('token', $options) ) {
            return false;
        }

        if ( $subscription_plan->amount <= 0 ) {
            return false;
        }

        $source = [
            'amount'      => $subscription_plan->amount,
            'currency'    => config('subscriptions.currency'),
            'source'      => $options[ 'token' ],
            'description' => $subscription_plan->description
        ];

        if ( is_null($this->stripe_id) ) {
            $customer = Customer::create([
                'email'  => $this->email,
                'source' => $options[ 'token' ],
            ], $this->options());

            if ( is_null($customer) || !$customer ) {
                return false;
            }

            $card = Customer::retrieveSource($customer->id, $customer->default_source, null, $this->options());

            if ( is_null($card) || !$card ) {
                return false;
            }

            $this->update([
                'stripe_id'      => $customer->id,
                'card_brand'     => $card->brand,
                'card_last_four' => $card->last4
            ]);

            array_push($source, [
                'customer' => $customer->id
            ]);
            unset($source[ 'source' ]);
        }

        $charge = StripeCharge::create($source, $this->options());

        if ( !$charge ) {
            return false;
        }


        return true;

    }

    /**
     * @param array $options
     * @return array
     */
    public function options ( array $options = [] ) {
        return Subscriptions::options($options);
    }
}
