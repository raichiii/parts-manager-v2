<?php

namespace App\Services\Order;

use Illuminate\Support\Facades\DB;
use App\Models\Order\UnregisteredList;

class UnregisteredListService
{
    public function create($name, $type)
    {
        $unregistedList = UnregisteredList::where(['name' => $name, 'type' => $type])->first();
        if (is_null($unregistedList)) {
            try {
                $unregistedList = DB::transaction(function () use ($name, $type) {
                    $unregistedList = new UnregisteredList;
                    $unregistedList->type = $type;
                    $unregistedList->name = $name;
                    $unregistedList->save();
                    return UnregisteredList::find($unregistedList->id);
                });
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage());
            }
        }
        return $unregistedList;
    }
}
