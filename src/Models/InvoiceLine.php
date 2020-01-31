<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

class InvoiceLine extends Model {
    use UsesUuid;

    protected $fillable = [
        'invoice_id',
        'subscription_id',
        'plan_id',
        'charge_id',
        'currency',
        'description',
        'amount',
        'quantity',
        'period_start',
        'period_end',
    ];

    protected $dates = [
        'period_start',
        'period_end'
    ];

    public function subscription() {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }

    public function plan() {
        return $this->hasOne(SubscriptionPlan::class, 'id', 'plan_id');
    }

}
