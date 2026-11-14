<?php

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use App\Models\Address\Company;

class Other extends Model
{
    protected $guarded = ['id', 'created_at'];

    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    public function maker()
    {
        return $this->hasOneThrough(Company::class, Thing::class, 'id', 'id', 'thing_id', 'maker_id');
    }
}
