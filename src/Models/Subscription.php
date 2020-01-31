<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

/**
 * MadeByMikkel\Subscriptions\Models\Subscription
 *
 * @property string $id
 * @property int $user_id
 * @property string $plan_id
 * @property int $cancel_at_period_end
 * @property string|null $period_start
 * @property string|null $period_end
 * @property string|null $cancelled_at
 * @property string|null $trial_start
 * @property string|null $trial_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \MadeByMikkel\Subscriptions\Models\SubscriptionPlan $plan
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereCancelAtPeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription wherePeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription wherePeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereTrialEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereTrialStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereUserId($value)
 * @mixin \Eloquent
 */
class Subscription extends Model {
    use UsesUuid;

    protected $fillable = [
        'user_id',
        'plan_id',
        'period_start',
        'period_end',
    ];

    public function plan() {
        return $this->hasOne(SubscriptionPlan::class, 'id', 'plan_id');
    }
}
