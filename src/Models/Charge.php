<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

class Charge extends Model {
    use UsesUuid;

    protected $fillable = [
        'user_id',
        'plan_id',
        'invoice_id',
        'charge_id',
        'amount',
        'amount_refunded',
        'paid',
        'refunded',
    ];

    protected $dates = [
        'paid',
        'refunded'
    ];

}
