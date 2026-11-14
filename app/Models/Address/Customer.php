<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at'];
    protected $casts = ['phones' => 'json', 'faxes' => 'json', 'emails' => 'json'];

    /**
     * 顧客はオフィスに属すため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function Office()
    {
        return $this->belongsTo(Office::class);
    }
}
