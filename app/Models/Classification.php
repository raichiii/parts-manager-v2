<?php

namespace App\Models;

use App\Models\Address\Company;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    public $incrementing = false;

    /**
     * ポリモーフィック 多対多
     */
    public function companies()
    {
        return $this->morphedByMany(Company::class, 'classifiable');
    }

    /**
     * 指定したタイプの分類を含むクエリーのスコープ
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
