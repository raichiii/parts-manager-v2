<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Requests\Other\AccountListRequest;
use App\Models\Other\AccountList;
use App\Services\Other\AccountListService;
use Illuminate\Support\Facades\Redirect;

class AccountListController extends Controller
{
    private $accountListService;

    public function __construct(AccountListService $accountListService)
    {
        $this->middleware(['can:isSystemAdmin']);
        $this->accountListService = $accountListService;
    }

    /**
     * アカウントの一覧
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $accountLists = AccountList::with('company', 'user')->get();
        return view('other.accountLists.index', compact('accountLists'));
    }

    /**
     * アカウントの作成画面
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('other.accountLists.create');
    }

    /**
     * アカウントの作成
     * @param App\Http\Requests\Other\AccountListRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(AccountListRequest $request)
    {
        try {
            $accountList = $this->accountListService->create($request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('accountLists.show', [$accountList])->with('success', 'アカウントが追加されました');
    }

    /**
     * アカウントの表示
     * @param \App\Models\Other\AccountList $accountList
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(AccountList $accountList)
    {
        $accountList = $accountList->load(['company', 'user']);
        return view('other.accountLists.show', compact('accountList'));
    }

    /**
     * アカウントの編集画面
     * @param \App\Models\Other\AccountList $accountList
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(AccountList $accountList)
    {
        $accountList = $accountList->load(['company', 'user']);
        return view('other.accountLists.edit', compact('accountList'));
    }


    /**
     * アカウントの情報更新
     * @param \App\Models\Other\AccountList $accountList
     * @param \App\Http\Requests\Other\AccountListRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountList $accountList, AccountListRequest $request)
    {
        try {
            $accountList = $this->accountListService->update($accountList, $request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('accountLists.show', [$accountList])->with('success', '変更が保存されました');
    }

    /**
     * アカウントの削除
     * @param \App\Models\Other\AccountList $accountList
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(AccountList $accountList)
    {
        try {
            $this->accountListService->destroy($accountList);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('accountLists.index')->with('success', '仕入れ先用アカウントを削除しました');
    }
}
