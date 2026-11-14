<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[UseFactory(CustomerFactory::class)]
class Customer extends Model
{
    use HasFactory;

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
