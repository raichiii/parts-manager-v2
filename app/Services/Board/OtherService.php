<?php

namespace App\Services\Board;

use Illuminate\Support\Facades\DB;
use App\Models\Board\Other;
use App\Http\Requests\Board\OtherRequest;

class OtherService
{
    /**
     * その他の作成
     * @param App\Http\Requests\Board\OtherRequest $request
     * @return App\Models\Board\Other
     * @throws \Exception
     */
    public function create(OtherRequest $request)
    {
        try {
            $other = DB::transaction(function () use ($request) {
                $other = new Other;
                $other->thing_id = $request->thing_id;
                $other->name = $request->name;
                $other->short_name = $request->short_name;
                $other->english_name = $request->english_name;
                $other->note = $request->note;
                $other->save();

                return Other::find($other->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $other;
    }

    /**
     * その他の変更
     * @param App\Models\Board\Other $other
     * @param Illuminate\Http\Request $request
     * @return App\Models\Board\Other
     * @throws \Exception
     */
    public function update(Other $other, OtherRequest $request)
    {
        try {
            $other = DB::transaction(function () use ($request, $other) {
                $other->name = $request->name;
                $other->short_name = $request->short_name;
                $other->english_name = $request->english_name;
                $other->note = $request->note;
                $other->save();

                return $other;
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $other;
    }

    /**
     * その他の削除
     * @param App\Models\Board\Other $other
     * @throws \Exception
     */
    public function destroy(Other $other)
    {
        try {
            DB::transaction(function () use ($other) {
                $other->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
