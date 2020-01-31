<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

/**
 * MadeByMikkel\Subscriptions\Models\InvoiceLine
 *
 * @property string $id
 * @property string $invoice_id
 * @property string|null $subscription_id
 * @property string|null $plan_id
 * @property string|null $charge_id
 * @property string $currency
 * @property string|null $description
 * @property int $amount
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $period_start
 * @property \Illuminate\Support\Carbon|null $period_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \MadeByMikkel\Subscriptions\Models\SubscriptionPlan $plan
 * @property-read \MadeByMikkel\Subscriptions\Models\Subscription $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine wherePeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine wherePeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\InvoiceLine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
