<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Individual extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at'];
    protected $casts = ['phones' => 'json', 'faxes' => 'json', 'emails' => 'json', 'urls' => 'json'];

    /**
     * 個人は県に属す
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }
}
