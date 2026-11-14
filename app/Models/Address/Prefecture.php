<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    //
    protected $guarded = ['id'];

    /**
     * オフィスは県に属すため
     * 一対多
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function offices()
    {
        return $this->hasMany(Office::class);
    }
}
