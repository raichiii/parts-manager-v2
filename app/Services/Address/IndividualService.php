<?php

namespace App\Services\Address;

use App\Models\Address\Individual;
use App\Http\Requests\Address\IndividualRequest;

use Illuminate\Support\Facades\DB;

class IndividualService
{
    /**
     * 個人の作成
     * @param App\Http\Requests\Address\IndividualRequest $request
     * @return App\Models\Address\Individual
     */
    public function create(IndividualRequest $request)
    {
        try {
            foreach ($request->input() as $key => $values) {
                if ($key == 'phones' || $key == 'faxes' || $key == 'emails' || $key == 'urls') {
                    $values = array_values(array_filter($values));
                    $request->merge([$key => $values]);
                }
            }
            $individual = DB::transaction(function () use ($request) {
                $individual = new Individual;
                $individual->name = $request->name;
                $individual->furigana = $request->furigana;
                $individual->english_name = $request->english_name;
                $individual->postalcode = $request->postalcode;
                $individual->prefecture_id = $request->prefecture_id;
                $individual->address = $request->address;
                $individual->phones = $request->phones;
                $individual->faxes = $request->faxes;
                $individual->emails = $request->emails;
                $individual->urls = $request->urls;
                $individual->note = $request->note;
                $individual->save();

                return Individual::find($individual->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $individual;
    }

    /**
     * 個人の変更
     * @param App\Http\Requests\Address\IndividualRequest $request
     * @param App\Models\Address\Individual $individual
     * @return App\Models\Address\Individual
     */
    public function update(IndividualRequest $request, Individual $individual)
    {
        try {
            foreach ($request->input() as $key => $values) {
                if ($key == 'phones' || $key == 'faxes' || $key == 'emails' || $key == 'urls') {
                    $values = array_values(array_filter($values));
                    $request->merge([$key => $values]);
                }
            }
            $individual = DB::transaction(function () use ($request, $individual) {
                $individual->name = $request->name;
                $individual->furigana = $request->furigana;
                $individual->english_name = $request->english_name;
                $individual->postalcode = $request->postalcode;
                $individual->prefecture_id = $request->prefecture_id;
                $individual->address = $request->address;
                $individual->phones = $request->phones;
                $individual->faxes = $request->faxes;
                $individual->emails = $request->emails;
                $individual->urls = $request->urls;
                $individual->note = $request->note;
                $individual->save();

                return Individual::find($individual->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $individual;
    }

    /**
     * 個人の削除
     * @param  App\Models\Address\Individual $individual
     * @throws \Exception
     */
    public function destroy($individual)
    {
        try {
            DB::transaction(function () use ($individual) {
                $individual->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
