<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A vehicle has many keys
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function keys() {
        return $this->hasMany('App\Models\Key');
    }
}
