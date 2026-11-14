<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at'];
    protected $casts = ['phones' => 'json', 'faxes' => 'json', 'emails' => 'json'];

    /**
     * オフィスは会社に属すため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * オフィスは複数の顧客を持つ
     * 一対多
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * オフィスは県に属す
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }
}
