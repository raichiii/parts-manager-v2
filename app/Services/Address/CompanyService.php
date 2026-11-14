<?php

namespace App\Services\Address;

use App\Models\Address\Company;
use App\Http\Requests\Address\CompanyRequest;

use Illuminate\Support\Facades\DB;

class CompanyService
{
    /**
     * 会社の作成
     * @param App\Http\Requests\Address\CompanyRequest $request
     * @return App\Models\Address\Company
     * @throws \Exception
     */
    public function create(CompanyRequest $request)
    {
        try {
            $urls = array_values(array_filter($request->urls));
            $company = DB::transaction(function () use ($request, $urls) {
                $company = new Company;
                $company->name = $request->name;
                $company->furigana = $request->furigana;
                $company->english_name = $request->english_name;
                $company->short_name = $request->short_name;
                $company->short_english_name = $request->short_english_name;
                $company->type = $request->type;
                $company->type_side = $request->type_side;
                $company->note = $request->note;
                $company->urls = $urls;
                $company->save();
                $company->classifications()->sync($request->classifications);

                return Company::find($company->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $company;
    }

    /**
     * 会社の変更
     * @param App\Http\Requests\Address\CompanyRequest $request
     * @param  App\Models\Address\Company $company
     * @return App\Models\Address\Company
     * @throws \Exception
     */
    public function update(CompanyRequest $request, Company $company)
    {
        try {
            $urls = array_values(array_filter($request->urls));
            $company = DB::transaction(function () use ($request, $company, $urls) {
                $company->name = $request->name;
                $company->furigana = $request->furigana;
                $company->english_name = $request->english_name;
                $company->short_name = $request->short_name;
                $company->short_english_name = $request->short_english_name;
                $company->type = $request->type;
                $company->type_side = $request->type_side;
                $company->note = $request->note;
                $company->urls = $urls;
                $company->save();
                $company->classifications()->sync($request->classifications);

                return Company::find($company->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $company;
    }

    /**
     * 会社の削除
     * @param  App\Models\Address\Company $company
     * @throws \Exception
     */
    public function destroy(Company $company)
    {
        try {
            DB::transaction(function () use ($company) {
                $company->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
