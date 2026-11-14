<?php

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use App\Models\Address\Company;

class PrintCircuitBoard extends Model
{
    protected $guarded = ['id', 'created_at'];
    protected $casts = [
        'ordered_at' => 'datetime',
    ];

    /**
     * 生基板は商品と対応するため
     * 一対一
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    /**
     * 生基板はメーカーと対応するため
     * 一対一
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function maker()
    {
        return $this->hasOneThrough(Company::class, Thing::class, 'id', 'id', 'thing_id', 'maker_id');
    }

    /**
     * 生基板は複数の基板を所持するため
     * 一対多
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function mountedBoards()
    {
        return $this->hasMany(MountedBoard::class);
    }
}
