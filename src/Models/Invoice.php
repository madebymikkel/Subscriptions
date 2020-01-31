<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

/**
 * MadeByMikkel\Subscriptions\Models\Invoice
 *
 * @property string $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\MadeByMikkel\Subscriptions\Models\InvoiceLine[] $lines
 * @property-read int|null $lines_count
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MadeByMikkel\Subscriptions\Models\Invoice whereUserId($value)
 * @mixin \Eloquent
 */
class Invoice extends Model {
    use UsesUuid;

    protected $fillable = [
        'user_id',
    ];


    public function lines () {
        return $this->hasMany(InvoiceLine::class, $this->getForeignKey());
    }
}
