<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Services\Address\CustomerService;
use App\Models\Address\Company;
use App\Models\Address\Office;
use App\Models\Address\Customer;
use App\Http\Requests\Address\CustomerRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /**
     * 顧客一覧
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office|null
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Company $company, ?Office $office = null)
    {
        if (isset($office)) {
        } else {
            $customers = Company::find($company->id)->customers;
        }
        return view('address.customers.index', compact('customers'));
    }

    /**
     * 顧客の作成のページを返す
     * @param App\Models\Address\Compnay $company|null
     * @param App\Models\Address\Office $office|null
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(?Company $company = null, ?Office $office = null)
    {
        $companies = Company::all();
        return view('address.customers.create', compact('companies'));
    }

    /**
     * 顧客の作成
     * @param App\Http\Requests\Address\CustomerRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(CustomerRequest $request)
    {
        try {
            $customer = $this->customerService->create($request);
            $office = $customer->office;
            $company = $office->company;
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('customers.show', [$company, $office, $customer])->with('success', '顧客が追加されました');
    }

    /**
     * 顧客の表示
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office
     * @param App\Models\Address\Customer $customer
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Company $company, Office $office, Customer $customer)
    {
        return view('address.customers.show', compact('customer'));
    }

    /**
     * 顧客の編集ページ
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office
     * @param App\Models\Address\Customer $customer
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Company $company, Office $office, Customer $customer)
    {
        return view('address.customers.edit', compact('customer'));
    }

    /**
     * 顧客の情報の変更
     * @param App\Http\Requests\Address\CustomerRequest $request
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office
     * @param App\Models\Address\Customer $customer
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(CustomerRequest $request, Company $company, Office $office, Customer $customer)
    {
        try {
            $customer = $this->customerService->update($request, $customer);
            $office = $customer->office;
            $company = $office->company;
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('customers.show', [$company, $office, $customer])->with('success', '変更を保存しました');
    }

    /**
     * 顧客の削除
     * @param App\Models\Address\Compnay $company
     * @param App\Models\Address\Office $office
     * @param App\Models\Address\Customer $customer
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Company $company, Office $office, Customer $customer)
    {
        try {
            $this->customerService->destroy($customer);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('customers.company.index', ['company' => $company])->with('success', '顧客を削除しました');
    }
}
