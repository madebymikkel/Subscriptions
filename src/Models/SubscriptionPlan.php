<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

/**
 * MadeByMikkel\Subscriptions\Models\SubscriptionPlan
 *
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property int $amount
 * @property int $interval
 * @property int $trial_period_days
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereActive( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereAmount( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereDeletedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereDescription( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereInterval( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereTitle( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereTrialPeriodDays( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\SubscriptionPlan whereUpdatedAt( $value )
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\MadeByMikkel\Subscriptions\Models\Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 */
class SubscriptionPlan extends Model {
    use UsesUuid;

    public function subscriptions () {
        return $this->hasMany(Subscription::class, $this->getForeignKey());
    }
}
