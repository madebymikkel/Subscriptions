<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

class Subscription extends Model {
    use UsesUuid;

    protected $fillable = [
        'user_id',
        'plan_id',
        'period_start',
        'period_end',
    ];

}
