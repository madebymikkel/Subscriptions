<?php

namespace MadeByMikkel\Subscriptions;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use MadeByMikkel\Subscriptions\Exceptions\AlreadySubscribedException;
use MadeByMikkel\Subscriptions\Exceptions\FailedToCreateSubscription;
use MadeByMikkel\Subscriptions\Exceptions\InvalidAmountException;
use MadeByMikkel\Subscriptions\Exceptions\PlanNotFoundException;
use MadeByMikkel\Subscriptions\Models\Charge;
use MadeByMikkel\Subscriptions\Models\Subscription;
use MadeByMikkel\Subscriptions\Models\SubscriptionPlan;
use Stripe\Charge as StripeCharge;
use Stripe\Customer;

trait Subscribable {

    /**
     * @param $plan_id
     * @param array $options
     * @param \Closure|null $fallback
     * @return mixed
     * @throws AlreadySubscribedException
     * @throws InvalidAmountException
     * @throws PlanNotFoundException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function beginSubscription ( $plan_id, array $options = [], \Closure $fallback = null ) {

        if ( $this->hasSubscription() ) {
            throw new AlreadySubscribedException('The user with the id ' . $this->id . ' is already a subscriber');
        }

        $subscription_plan = SubscriptionPlan::find($plan_id);

        if ( !$subscription_plan ) {
            throw new PlanNotFoundException('The plan with the id ' . $subscription_plan->id . ' doesn\'t exist.');
        }

        $charge = $this->charge($subscription_plan, $options);

        if ( !$charge ) {
            throw new QueryException('Charge wasn\'t able to be created.', null, null);
        }

        $subscription = $this->createSubscription($subscription_plan);

        return $subscription ?
            $this->subscribed($subscription, $charge)
            : $fallback();

    }

    /**
     * The subscription was created successfully.
     *
     * @param Subscription $subscription
     * @param $charge
     * @return mixed
     */
    protected function subscribed ( $subscription, $charge ) {
    }

    /**
     * @param $subscription_plan
     * @return mixed
     */
    private function createSubscription ( $subscription_plan ) {
        return $this->subscriptions()->create([
            'plan_id'      => $subscription_plan->id,
            'period_start' => Carbon::now(),
            'period_end'   => Carbon::now()->addMonths($subscription_plan->interval),
        ]);
    }

    /**
     * @return mixed
     */
    public function hasSubscription () {
        return !is_null($this->subscription);
    }

    /**
     * @param $subscription
     * @return mixed
     * @throws FailedToCreateSubscription
     * @throws InvalidAmountException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function renewSubscription ( $subscription ) {
        $charge = $this->charge($subscription->plan);

        if ( !$charge ) {
            throw new QueryException('Couldn\'t charge the customer.', null, null);
        }

        $new_subscription = $this->createSubscription($subscription->plan);

        if (is_null($new_subscription)) {
            throw new FailedToCreateSubscription('Failed to create subscription on renewal.');
        }

        return $subscription->delete();
    }

    /**
     * @param $subscription_plan
     * @param array $options
     * @return bool
     * @throws InvalidAmountException
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function charge ( $subscription_plan, array $options = [] ) {

        if ( $subscription_plan->amount <= 0 ) {
            throw new InvalidAmountException('The plan with the id ' . $subscription_plan->id . ' has an invalid amount of ' . $subscription_plan->amount . '.');
        }

        if ( !$this->hasStripeId() ) {
            $this->createStripeCustomer($options);
        }

        return $this->createCharge($subscription_plan, $this->createStripeCharge($subscription_plan));

    }

    private function createCharge ( $subscription_plan, $stripe_charge ) {
        return $this->charges()->create([
            'plan_id'   => $subscription_plan->id,
            'charge_id' => $stripe_charge->id,
            'amount'    => $stripe_charge->amount,
            'paid'      => Carbon::now()
        ]);
    }


    /**
     * @param $subscription_plan
     * @return StripeCharge
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function createStripeCharge ( $subscription_plan ) {
        return StripeCharge::create([
            'amount'      => $subscription_plan->amount,
            'currency'    => config('subscriptions.currency'),
            'description' => $subscription_plan->description,
            'customer'    => $this->stripe_id
        ], $this->options());
    }

    /**
     * @param array $options
     * @return bool
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function createStripeCustomer ( array $options = [] ) {

        $customer = Customer::create([
            'email'  => $this->email,
            'source' => $options[ 'token' ],
        ], $this->options());

        $card = $this->getDefaultCard($customer->id, $customer->default_source);

        return $this->updateCard($customer->id, $card->brand, $card->last4);

    }

    /**
     * @param $customer_id
     * @param $default_source
     * @return \Stripe\ApiResource
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function getDefaultCard ( $customer_id, $default_source ) {
        return Customer::retrieveSource($customer_id, $default_source, null, $this->options());
    }

    /**
     * @param $customer_id
     * @param $card_brand
     * @param $card_last_four
     * @return mixed
     */
    private function updateCard ( $customer_id, $card_brand, $card_last_four ) {
        return $this->update([
            'stripe_id'      => $customer_id,
            'card_brand'     => $card_brand,
            'card_last_four' => $card_last_four
        ]);
    }

    /**
     * @return Charge
     */
    public function charges () {
        return $this->hasMany(Charge::class, $this->getForeignKey());
    }

    /**
     * @return Subscription
     */
    public function subscriptions () {
        return $this->hasMany(Subscription::class, $this->getForeignKey());
    }

    /**
     * @return Subscription
     */
    public function subscription () {
        return $this->hasOne(Subscription::class, $this->getForeignKey());
    }

    /**
     * @return bool
     */
    public function hasStripeId () {
        return !is_null($this->stripe_id);
    }

    /**
     * @param array $options
     * @return array
     */
    public function options ( array $options = [] ) {
        return Subscriptions::options($options);
    }
}
