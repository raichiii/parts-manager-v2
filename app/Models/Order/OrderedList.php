<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Address\Company;
use App\Models\Board\Thing;
use App\Models\Board\Part;
use App\Models\Board\PrintCircuitBoard;
use App\Models\Board\Other;

use Database\Factories\OrderedListFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[UseFactory(OrderedListFactory::class)]
class OrderedList extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'requested_user_id', 'requested_at', 'created_at'];
    protected $casts = [
        'purchase_at' => 'datetime',
        'requested_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * 商品が複数の依頼を持つため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    public function printCircuitBoard()
    {
        return $this->hasOneThrough(PrintCircuitBoard::class, Thing::class, 'id', 'thing_id', 'thing_id', 'id');
    }

    public function part()
    {
        return $this->hasOneThrough(Part::class, Thing::class, 'id', 'thing_id', 'thing_id', 'id');
    }

    public function other()
    {
        return $this->hasOneThrough(Other::class, Thing::class, 'id', 'thing_id', 'thing_id', 'id');
    }

    /**
     * 部品はジョブに属すため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * 会社が複数の依頼を持つため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Company::class, 'supplier_id');
    }

    public function lotUnit()
    {
        return $this->belongsTo(LotUnit::class);
    }

    /**
     * ユーザーが複数の購入依頼を作成するため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function requestedUser()
    {
        return $this->belongsTo(User::class, 'requested_user_id');
    }

    /**
     * ユーザーが複数発注するため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function orderedUser()
    {
        return $this->belongsTo(User::class, 'ordered_user_id');
    }

    /**
     * 注文依頼リストが未登録の型番と仕入れ先を持つ可能性があるため
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function unregisteredModels()
    {
        return $this->morphMany(UnregisteredModel::class, 'listable');
    }

    public function unregisteredSupplier()
    {
        return $this->unregisteredModels->where('type', 'Supplier')->first();
    }

    public function unregisteredModelNo()
    {
        return $this->unregisteredModels->where('type', 'ModelNo')->first();
    }
}
