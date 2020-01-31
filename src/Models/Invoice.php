<?php

namespace MadeByMikkel\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use MadeByMikkel\Subscriptions\Traits\UsesUuid;

class Invoice extends Model {
    use UsesUuid;

    protected $fillable = [
        'user_id',
    ];


    public function lines () {
        return $this->hasMany(InvoiceLine::class, $this->getForeignKey());
    }
}
