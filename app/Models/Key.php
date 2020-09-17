<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

//    /**
//     * A key belongs to a vehicle
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
//     */
//    public function vehicle() {
//        return $this->belongsTo('App\Models\Vehicle');
//    }

    /**
     * A key has many orders
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany('App\Models\Order');
    }
}
