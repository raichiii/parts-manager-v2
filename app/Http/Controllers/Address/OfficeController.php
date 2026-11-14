<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Models\Address\Company;
use App\Models\Address\Office;
use App\Models\Address\Prefecture;
use App\Services\Address\OfficeService;
use App\Http\Requests\Address\OfficeRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OfficeController extends Controller
{
    private $officeService;

    public function __construct(OfficeService $officeService)
    {
        $this->officeService = $officeService;
    }
    /**
     * 事務所一覧
     * @param App\Models\Address\Compnay $company
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Company $company)
    {
        $offices = $company->offices;
        return view('address.offices.index', compact('offices'));
    }

    /**
     * 事務所作成のページを返す
     * @param App\Models\Address\Compnay $company|null
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(?Company $company = null)
    {
        $prefectures = Prefecture::all();
        $companies = Company::all();
        return view('address.offices.create', compact('companies', 'prefectures'));
    }

    /**
     * 事務所の作成
     * @param App\Http\Requests\Address\OfficeRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(OfficeRequest $request)
    {
        try {
            $office = $this->officeService->create($request);
            $company = $office->company;
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('offices.index', $company)->with('success', '事務所が追加されました');
    }

    /**
     * 事務所の表示
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Company $company, Office $office)
    {
        return view('address.offices.show', compact('office'));
    }

    /**
     * 事務所の編集ページ
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Company $company, Office $office)
    {
        $prefectures = Prefecture::all();
        return view('address.offices.edit', compact('office', 'prefectures'));
    }

    /**
     * 事務所情報の変更
     * @param App\Http\Requests\Address\OfficeRequest $request
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(OfficeRequest $request, Company $company, Office $office)
    {
        try {
            $office = $this->officeService->update($request, $office);
            $company = $office->company;
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('offices.show', [$company, $office])->with('success', '変更を保存しました');
    }

    /**
     * 事務所の削除
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Company $company, Office $office)
    {
        try {
            if ($office->customers()->exists()) {
                return Redirect::back()
                    ->withErrors(['顧客が存在するため削除できません']);
            }
            $this->officeService->destroy($office);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('offices.index', ['company' => $company])->with('success', '事務所を削除しました');
    }

    public function getOfficesByCompany(Request $request)
    {
        $id = $request->query('company');
        $offces = Company::find($id)->offices;
        return response()->json($offces);
    }
}
