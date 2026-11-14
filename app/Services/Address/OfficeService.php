<?php

namespace App\Services\Address;

use App\Models\Address\Company;
use App\Models\Address\Office;
use App\Http\Requests\Address\OfficeRequest;

use Illuminate\Support\Facades\DB;

class OfficeService
{
    /**
     * 事務所の作成
     * @param App\Http\Requests\Address\OfficeRequest $request
     * @return App\Models\Address\Office
     */
    public function create(OfficeRequest $request)
    {
        try {
            foreach ($request->input() as $key => $values) {
                if ($key == 'phones' || $key == 'faxes' || $key == 'emails') {
                    $values = array_values(array_filter($values));
                    $request->merge([$key => $values]);
                }
            }
            $office = DB::transaction(function () use ($request) {
                $office = new Office;
                $office->company_id = $request->company_id;
                $office->name = $request->name;
                $office->postalcode = $request->postalcode;
                $office->prefecture_id = $request->prefecture_id;
                $office->address = $request->address;
                $office->building = $request->building;
                $office->phones = $request->phones;
                $office->faxes = $request->faxes;
                $office->emails = $request->emails;
                $office->note = $request->note;
                $office->save();

                return Office::find($office->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $office;
    }

    /**
     * 事務所の変更
     * @param App\Http\Requests\Address\OfficeRequest $request
     * @param App\Models\Address\Office $office
     * @return App\Models\Address\Office
     */
    public function update(OfficeRequest $request, Office $office)
    {
        try {
            foreach ($request->input() as $key => $values) {
                if ($key == 'phones' || $key == 'faxes' || $key == 'emails') {
                    $values = array_values(array_filter($values));
                    $request->merge([$key => $values]);
                }
            }
            $office = DB::transaction(function () use ($request, $office) {
                $office->company_id = $request->company_id;
                $office->name = $request->name;
                $office->postalcode = $request->postalcode;
                $office->prefecture_id = $request->prefecture_id;
                $office->address = $request->address;
                $office->building = $request->building;
                $office->phones = $request->phones;
                $office->faxes = $request->faxes;
                $office->emails = $request->emails;
                $office->note = $request->note;
                $office->save();

                return Office::find($office->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $office;
    }

    /**
     * 事務所の削除
     * @param  App\Models\Address\Company $company
     * @throws \Exception
     */
    public function destroy($office)
    {
        try {
            DB::transaction(function () use ($office) {
                $office->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
