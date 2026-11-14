<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\IndividualRequest;
use App\Models\Address\Prefecture;
use App\Models\Address\Individual;
use App\Services\Address\IndividualService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class IndividualController extends Controller
{
    private $individualService;

    public function __construct(IndividualService $individualService)
    {
        $this->individualService = $individualService;
    }

    /**
     * 個人一覧
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $individuals = Individual::all();
        return view('address.individuals.index', compact('individuals'));
    }

    /**
     * 個人作成ページ
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $prefectures = Prefecture::all();
        return view('address.individuals.create', compact('prefectures'));
    }

    /**
     * 個人の作成
     * @param App\Http\Requests\Address\IndividualRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(IndividualRequest $request)
    {
        try {
            $this->individualService->create($request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('individuals.index')->with('success', '個人が追加されました');
    }

    /**
     * 個人の表示
     * @param App\Models\Address\Individual $individual
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Individual $individual)
    {
        return view('address.individuals.show', compact('individual'));
    }

    /**
     * 個人の編集ページ
     * @param App\Models\Address\Individual $individual
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Individual $individual)
    {
        $prefectures = Prefecture::all();
        return view('address.individuals.edit', compact('individual', 'prefectures'));
    }

    /**
     * 個人情報の変更
     * @param App\Http\Requests\Address\IndividualRequest $request
     * @param App\Models\Address\Individual $individual
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(IndividualRequest $request, Individual $individual)
    {
        try {
            $individual = $this->individualService->update($request, $individual);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('individuals.show', ['individual' => $individual])->with('success', '変更を保存しました');
    }

    /**
     * 個人の削除
     * @param App\Models\Address\Individual $individual
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Individual $individual)
    {
        try {
            $this->individualService->destroy($individual);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('individuals.index')->with('success', '個人を削除しました');
    }
}
