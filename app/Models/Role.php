<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{
    use softDeletes;
    //
    public $incrementing = false;
    protected $casts = [
        'deleted_at' => 'datetime',
    ];
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * ロールを持つユーザーを取得
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * 指定したタイプのロールを含むクエリーのスコープ
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
