<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'truck_number', 'active'];

    /**
     * A technician has many orders
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany('App\Models\Order');
    }
}
