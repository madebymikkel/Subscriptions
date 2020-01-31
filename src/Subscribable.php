<?php

namespace MadeByMikkel\Subscriptions;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use MadeByMikkel\Subscriptions\Exceptions\AlreadySubscribedException;
use MadeByMikkel\Subscriptions\Exceptions\InvalidAmountException;
use MadeByMikkel\Subscriptions\Exceptions\PlanNotFoundException;
use MadeByMikkel\Subscriptions\Models\Subscription;
use MadeByMikkel\Subscriptions\Models\SubscriptionPlan;
use MadeByMikkel\Subscriptions\Models\Charge;
use Stripe\Charge as StripeCharge;
use Stripe\Customer;

trait Subscribable {

    private $redirectTo;

    /**
     * @param $plan_id
     * @param array $options
     * @return mixed
     * @throws AlreadySubscribedException
     * @throws InvalidAmountException
     * @throws PlanNotFoundException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function beginSubscription ( $plan_id, array $options = [] ) {

        if ( $this->hasSubscription() ) {
            throw new AlreadySubscribedException('The user with the id ' . $this->id . ' is already a subscriber');
        }

        $subscription_plan = SubscriptionPlan::find($plan_id);

        if ( !$subscription_plan ) {
            throw new PlanNotFoundException('The plan with the id ' . $subscription_plan->id . ' doesn\'t exist.');
        }

        $charge = $this->charge($subscription_plan, $options);

        $subscription = $this->createSubscription($subscription_plan);

        return $subscription ?
            $this->subscribed($subscription, $charge)
            : redirect($this->redirectPath());

    }

    public function redirectPath () {
        if ( method_exists($this, 'redirectTo') ) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    public function charges() {
        return $this->hasMany(Charge::class, $this->getForeignKey());
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class, $this->getForeignKey());
    }

    public function subscription() {
        return $this->hasOne(Subscription::class, $this->getForeignKey());
    }

    /**
     * The subscription was created successfully.
     *
     * @param Subscription $subscription
     * @return mixed
     */
    protected function subscribed ($subscription, $charge) {
    }

    /**
     * @param null $subscription_plan
     * @return bool
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
        return Subscription::whereUserId($this->id)->first();
    }

    /**
     * @param $subscription_plan
     * @param array $options
     * @return bool
     * @throws InvalidAmountException
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function charge ( $subscription_plan, array $options = [] ) {

        if ( !array_key_exists('token', $options) ) {
            throw new \InvalidArgumentException();
        }

        if ( $subscription_plan->amount <= 0 ) {
            throw new InvalidAmountException('The plan with the id ' . $subscription_plan->id . ' has an invalid amount of ' . $subscription_plan->amount . '.');
        }

        if ( !$this->hasStripeId() ) {
            return $this->createStripeCustomer($options);
        }

        $charge = $this->createCharge($subscription_plan, $this->createStripeCharge($subscription_plan));

        if ( !$charge ) {
            throw new QueryException('Charge wasn\'t able to be created.');
        }

        return $charge;

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
