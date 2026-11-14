<?php

namespace App\Models\Order;

use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Events\JobCreated;
use App\Models\Address\Company;
use App\Models\Order\RequestedList;
use App\Models\Order\OrderedList;
use App\Models\Order\PurchasedList;

#[UseFactory(JobFactory::class)]
class Job extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at'];

    // public static function boot()
    // {
    //     parent::boot();
    //     self::creating(function ($model) {
    //         event(new JobCreated($model));
    //     });
    // }

    /**
     * 一つの依頼元に複数のジョブが属す
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function requester()
    {
        return $this->belongsTo(Company::class, 'requester_id');
    }

    /**
     * 一つの納入先に複数のジョブが属す
     * 多対一
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function deliveryDestination()
    {
        return $this->belongsTo(Company::class, 'delivery_destination_id');
    }

    /**
     * 注文依頼は仕入れ先に属す
     */
    public function requestedLists()
    {
        return $this->hasMany(requestedList::class, 'supplier_id');
    }

    /**
     * 発注済みリストは仕入れ先に属す
     */
    public function orderedLists()
    {
        return $this->hasMany(OrderedList::class, 'supplier_id');
    }

    /**
     * 購入実績は仕入れ先に属す
     */
    public function purchasedLists()
    {
        return $this->hasMany(PurchasedList::class, 'supplier_id');
    }

    /**
     * 購入依頼の存在確認
     * @return bool
     */
    public function hasOrder()
    {
        return $this->requestedLists()->exists() ||
            $this->orderedLists()->exists() ||
            $this->purchasedLists()->exists();
    }
}
