<?php

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MountedBoard extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at'];

    /**
     * 複数の基板が一つの生基板に属すため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function PrintCircuitBoard()
    {
        return $this->belongsTo(PrintCircuitBoard::class);
    }

    /**
     * 基板はパーツから成るため
     * 多対多
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parts()
    {
        return $this->belongsToMany(Part::class)->withPivot('reference_number', 'is_mounted');
    }

    public static function getLatestMountedBoards($id)
    {
        $mountedBoards = MountedBoard::withTrashed()->where('print_circuit_board_id', $id)->distinct()->get(['version_name']);
        $results = [];
        foreach ($mountedBoards as $mountedBoard) {
            $latestMountedBoard = MountedBoard::withTrashed()->where('version_name', $mountedBoard->version_name)->orderBy('version', 'desc')->first();
            array_push($results, $latestMountedBoard);
        }
        return $results;
    }
}
