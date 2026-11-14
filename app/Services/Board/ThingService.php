<?php

namespace App\Services\Board;

use Illuminate\Support\Facades\DB;
use App\Models\Board\Thing;
use App\Models\Board\Part;
use App\Models\Address\Company;

class ThingService
{
    /**
     * 部品の作成
     * @param App\Http\Requests\Board\ThingRequest $request
     * @return App\Models\Board\Thing
     * @throws \Exception
     */
    public function create($request)
    {
        try {
            $thing = DB::transaction(function () use ($request) {
                $thing = new Thing;
                $thing->type = $request->type;
                $thing->model_no = $request->model_no;
                $thing->maker_id = $request->maker_id;
                $thing->save();

                return $thing;
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $thing;
    }

    /**
     * 部品の変更
     * @param App\Models\Board\Thing $thing
     * @param Illuminate\Http\Request $request
     * @return App\Models\Board\Thing
     * @throws \Exception
     */
    public function update(Thing $thing, $request)
    {
        try {
            $thing = DB::transaction(function () use ($request, $thing) {
                $thing->model_no = $request->model_no;
                $thing->maker_id = $request->maker_id;
                $thing->save();

                return $thing;
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $thing;
    }

    /**
     * 部品の削除
     * @param App\Models\Board\Thing $thing
     * @throws \Exception
     */
    public function destroy(Thing $thing)
    {
        try {
            DB::transaction(function () use ($thing) {
                $thing->forceDelete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
