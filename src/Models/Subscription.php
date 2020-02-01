<?php

namespace MadeByMikkel\Subscriptions\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

/**
 * MadeByMikkel\Subscriptions\Models\Subscription
 *
 * @property string $id
 * @property int $user_id
 * @property string $plan_id
 * @property \Illuminate\Support\Carbon|null $cancel_at_period_end
 * @property \Illuminate\Support\Carbon|null $period_start
 * @property \Illuminate\Support\Carbon|null $period_end
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property \Illuminate\Support\Carbon|null $trial_start
 * @property \Illuminate\Support\Carbon|null $trial_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \MadeByMikkel\Subscriptions\Models\SubscriptionPlan $plan
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereCancelAtPeriodEnd( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereCancelledAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereDeletedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription wherePeriodEnd( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription wherePeriodStart( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription wherePlanId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereTrialEnd( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereTrialStart( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription whereUserId( $value )
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription active()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription notOnGracePeriod()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Subscription onGracePeriod()
 */
class Subscription extends Model {
    use UsesUuid, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'period_start',
        'period_end',
    ];

    /**
     * Is the subscription active?
     *
     * @return bool
     */
    public function isActive () {
        return ( is_null($this->cancel_at_period_end) || $this->onGracePeriod() );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeCancelling ( $query ) {
        $query->whereNotNull('cancel_at_period_end')->where('cancel_at_period_end', '<=', Carbon::now());
    }

    /**
     * Filter query by past due.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopePastDue ( $query ) {
        $query->whereDate('period_end', '<=', Carbon::today());
    }

    /**
     * Filter query by active.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeActive ( $query ) {
        $query->whereNull('cancel_at_period_end');
    }

    /**
     * The plan connected to the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function plan () {
        return $this->hasOne(SubscriptionPlan::class, 'id', 'plan_id');
    }

    public function user () {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
