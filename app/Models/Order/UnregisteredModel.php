<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class UnregisteredModel extends Model
{
    protected $guarded = ['id', 'created_at'];

    public function UnregisteredModelable()
    {
        return $this->morphTo();
    }
}
