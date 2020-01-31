<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

/**
 * MadeByMikkel\Subscriptions\Models\Charge
 *
 * @property string $id
 * @property int $user_id
 * @property string|null $plan_id
 * @property string|null $invoice_id
 * @property string|null $charge_id
 * @property int $amount
 * @property int $amount_refunded
 * @property int $attempted_count
 * @property \Illuminate\Support\Carbon|null $paid
 * @property \Illuminate\Support\Carbon|null $refunded
 * @property string|null $attempted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereAmountRefunded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereAttempted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereAttemptedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereRefunded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Charge whereUserId($value)
 * @mixin \Eloquent
 */
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
