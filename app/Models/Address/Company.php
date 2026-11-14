<?php

namespace App\Models\Address;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Classification;
use App\Models\Board\Part;
use App\Models\Board\Thing;
use App\Models\Order\RequestedList;
use App\Models\Order\OrderedList;
use App\Models\Order\PurchasedList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(CompanyFactory::class)]
class Company extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at'];
    protected $casts = ['urls' => 'json'];
    /** 正式名称のための属性を追加 */
    protected $appends = ['full_name'];

    /**
     * オフィスは会社に属すため
     * 一対多
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    /**
     * オフィスを通じて顧客を取得
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Office::class);
    }

    /**
     * ポリモーフィック 多対多
     */
    public function classifications()
    {
        return $this->morphToMany(Classification::class, 'classifiable');
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

    /**
     * モノはメーカーに属すため
     * 一対多
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function things()
    {
        return $this->hasMany(Thing::class, 'maker_id');
    }

    /**
     * モノを通して部品を取得
     * 一対多
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function parts()
    {
        return $this->hasManyThrough(Part::class, Thing::class, 'maker_id');
    }

    public function hasClassification($name)
    {
        return $this->classifications()->where('name', $name)->exists();
    }

    /** 会社の正式名称ををfull_nameに */
    public function getFullNameAttribute()
    {
        $name = $this->name;
        if ($this->type != null) {
            $typeAlias = CMP_TYPE_ARRAY[$this->type];
            if ($this->type_side == CMP_TYPE_SIDE_MAE) {
                $name = $typeAlias . $name;
            } else if ($this->type_side == CMP_TYPE_SIDE_ATO) {
                $name = $name . $typeAlias;
            }
        }
        return $name;
    }
}
