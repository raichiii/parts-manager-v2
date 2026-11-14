<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Address\Company;
use App\Models\Classification;
use App\Services\Address\CompanyService;
use App\Http\Requests\Address\CompanyRequest;
use App\Services\Order\UnregisteredModelService;
use App\Services\Python\PythonService;

class CompanyController extends Controller
{
    private $companyService;
    private $unregisteredModelService;
    private $pythonService;

    public function __construct(CompanyService $companyService,
        UnregisteredModelService $unregisteredModelService,
        PythonService $pythonService)
    {
        $this->companyService = $companyService;
        $this->unregisteredModelService = $unregisteredModelService;
        $this->pythonService = $pythonService;
    }

    /**
     * 会社一覧
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        // $out = $this->pythonService->exec('calc_difflib');
        $companies = Company::with(['offices', 'customers', 'classifications'])->get();
        $classifications = Classification::ofType(CLS_TYPE_COMPANY)->get();
        return view('address.companies.index', compact('companies', 'classifications'));
    }

    /**
     * 会社作成のページを表示
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $classifications = Classification::OfType(CLS_TYPE_COMPANY)->get();
        return view('address.companies.create', compact('classifications'));
    }

    /**
     * 会社を作成
     * @param \App\Http\Requests\Address\CompanyRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(CompanyRequest $request)
    {
        try {
            $company = $this->companyService->create($request);
            $this->unregisteredModelService->update($company, 'Supplier');
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('companies.index')->with('success', '会社が追加されました');
    }

    /**
     * 会社編集ページ
     *
     * @param App\Models\Address\Compnay $company
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Company $company)
    {
        $classifications = Classification::OfType(CLS_TYPE_COMPANY)->get();
        $checkedClassifications = $company->classifications->pluck('id')->toArray();
        return view('address.companies.edit', compact('company', 'classifications', 'checkedClassifications'));
    }

    /**
     * 会社の情報を変更
     *
     * @param \App\Http\Requests\Address\CompanyRequest $request
     * @param \App\Models\Address\Company $company
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(CompanyRequest $request, Company $company)
    {
        try {
            $this->companyService->update($request, $company);
            $this->unregisteredModelService->update($company, 'Supplier');
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('companies.show', ['company' => $company])->with('success', '変更を保存しました');
    }


    /**
     * 指定した会社を表示
     * @param App\Models\Address\Compnay $company
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Company $company)
    {
        return view('address.companies.show', compact('company'));
    }


    /**
     * 指定した会社を削除
     * @param App\Models\Address\Compnay $company
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Company $company)
    {
        try {
            if ($company->offices()->exists() || $company->customers()->exists()) {
                return Redirect::back()
                    ->withErrors(['事務所または顧客が存在するため削除できません']);
            } else if ($company->things()->exists()) {
                return Redirect::back()
                    ->withErrors(['メーカーに部品が登録されているため削除できません']);
            }
            $this->companyService->destroy($company);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('companies.index')->with('success', '会社を削除しました');
    }

    /**
     * 全ての会社取得のAPI
     * @return Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function getAll()
    {
        $companies = Company::all();
        return response()->json($companies);
    }

    /**
     * 会社検索用のAPI
     * @param Request $request
     * @return Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function search(Request $request)
    {
        $keyword = $request->query('query');
        if ($request->exists('type')) {
            return $this->searchWithClassification($request);
        }
        $companies = Company::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('furigana', 'like', '%' . $keyword . '%')
            ->orWhere('english_name', 'like', '%' . $keyword . '%')
            ->orWhere('short_name', 'like', '%' . $keyword . '%')
            ->orWhere('short_english_name', 'like', '%' . $keyword . '%')
            ->get();
        return response()->json($companies);
    }

    /**
     * 分類付き会社検索用のAPI
     * @param Request $request
     * @return Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function searchWithClassification(Request $request)
    {
        $keyword = $request->query('query');
        $type = $request->query('type');
        $classification = Classification::OfType(CLS_TYPE_COMPANY)->where('name', $type)->first();
        try {
            $companies = $classification->companies()->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
                $query->orWhere('furigana', 'like', '%' . $keyword . '%');
                $query->orWhere('english_name', 'like', '%' . $keyword . '%');
                $query->orWhere('short_name', 'like', '%' . $keyword . '%');
                $query->orWhere('short_english_name', 'like', '%' . $keyword . '%');
            })->limit(10)->get();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 422);
        }
        return response()->json($companies);
    }
}
