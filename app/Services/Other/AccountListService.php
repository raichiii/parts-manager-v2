<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\DB;
use App\Models\Other\AccountList;
use App\Models\Address\Company;
use App\Http\Requests\Other\AccountListRequest;


class AccountListService
{
    /**
     * アカウントの作成
     * @param App\Http\Requests\Other\AccountListRequest $request
     * @return App\Models\Other\AccountList
     * @throws \Exception
     */
    public function create(AccountListRequest $request)
    {
        // 仕入れ先の判定
        if (!(($request->company_id) && Company::find($request->company_id)->hasClassification(CLS_NAME_SUPPLIER))) {
            throw new \Exception("選択した会社は仕入れ先ではありません");
        }
        try {
            $accountList = DB::transaction(function () use ($request) {
                $accountList = new AccountList;
                $accountList->company_id = $request->company_id;
                $accountList->user_id = auth()->id();
                $accountList->name = $request->name;
                $accountList->password = $request->password;
                $accountList->note = $request->note;
                $accountList->save();

                return AccountList::find($accountList->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $accountList;
    }

    /**
     * アカウントの情報更新
     * @param \App\Models\Other\AccountList $accountList
     * @param App\Http\Requests\Other\AccountListRequest $request
     * @return App\Models\Other\AccountList
     * @throws \Exception
     */
    public function update(AccountList $accountList, AccountListRequest $request)
    {
        // 仕入れ先の判定
        if (!(($request->company_id) && Company::find($request->company_id)->hasClassification(CLS_NAME_SUPPLIER))) {
            throw new \Exception("選択した会社は仕入れ先ではありません");
        }
        try {
            $accountList = DB::transaction(function () use ($request, $accountList) {
                $accountList->company_id = $request->company_id;
                $accountList->user_id = auth()->id();
                $accountList->name = $request->name;
                $accountList->password = $request->password;
                $accountList->note = $request->note;
                $accountList->save();

                return AccountList::find($accountList->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $accountList;
    }

    /**
     * アカウントの削除
     * @param \App\Models\Other\AccountList $accountList
     * @throws \Exception
     */
    public function destroy(AccountList $accountList)
    {
        try {
            DB::transaction(function () use ($accountList) {
                $accountList->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
