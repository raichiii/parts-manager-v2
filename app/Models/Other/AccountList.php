<?php

namespace App\Models\Other;

use App\User;
use App\Models\Address\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountList extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at'];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * ユーザーに属すため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 会社に属すため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
