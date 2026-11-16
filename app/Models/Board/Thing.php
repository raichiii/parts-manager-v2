<?php

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Address\Company;

use Database\Factories\ThingFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


#[UseFactory(ThingFactory::class)]
class Thing extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = ['id', 'created_at'];

    public function printCircuitBoard()
    {
        return $this->hasOne(PrintCircuitBoard::class, 'thing_id', 'id');
    }

    public function part()
    {
        return $this->hasOne(Part::class, 'thing_id', 'id');
    }

    public function other()
    {
        return $this->hasOne(Other::class, 'thing_id', 'id');
    }

    /**
     * 商品は会社に属すため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function maker()
    {
        return $this->belongsTo(Company::class, 'maker_id');
    }
}
