<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status', 'note'];

    /**
     * Order status names
     */
    const STATUSES = array(
        'PENDING'  => 'pending',
        'APPROVED' => 'approved',
        'DECLINED' => 'declined'
    );

    /**
     * An order belongs to a technician
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function technician() {
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * An order belongs to a key
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function key() {
        return $this->belongsTo('App\Models\Key');
    }

    /**
     * An order belongs to a vehicle
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function vehicle() {
        return $this->belongsTo('App\Models\Vehicle');
    }
}
