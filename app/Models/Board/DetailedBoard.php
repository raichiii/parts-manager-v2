<?php

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Board\MountedBoard;
use App\User;

class DetailedBoard extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at'];

    /**
     * 在庫基板は生基板に属するため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function mountedBoard()
    {
        return $this->belongsTo(MountedBoard::class);
    }

    /**
     * 在庫基板はユーザに属するため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
