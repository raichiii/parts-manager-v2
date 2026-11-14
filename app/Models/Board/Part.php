<?php

namespace App\Models\Board;

use Illuminate\Database\Eloquent\Model;
use App\Models\Address\Company;
use App\Models\Order\RequestedList;
use App\Models\Order\OrderedList;
use App\Models\Order\PurchasedList;

class Part extends Model
{
    protected $guarded = ['id', 'created_at'];

    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    public function maker()
    {
        return $this->hasOneThrough(Company::class, Thing::class, 'id', 'id', 'thing_id', 'maker_id');
    }

    /**
     * 基板は部品から成るため
     * 多対多
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function mountedBoards()
    {
        return $this->belongsToMany(MountedBoard::class);
    }

    /**
     * 注文依頼は仕入れ先に属すため
     */
    public function requestedLists()
    {
        return $this->hasMany(requestedList::class, 'supplier_id');
    }

    /**
     * 発注済みリストは仕入れ先に属すため
     */
    public function orderedLists()
    {
        return $this->hasMany(OrderedList::class, 'supplier_id');
    }

    /**
     * 購入実績は仕入れ先に属すため
     */
    public function purchasedLists()
    {
        return $this->hasMany(PurchasedList::class, 'supplier_id');
    }
}
