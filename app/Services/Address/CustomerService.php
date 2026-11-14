<?php

namespace App\Services\Address;

use App\Http\Requests\Address\CustomerRequest;
use App\Models\Address\Customer;

use Illuminate\Support\Facades\DB;

class CustomerService
{
    /**
     * 顧客の作成
     * @param App\Http\Requests\Address\CustomerRequest $request
     * @return App\Models\Address\Customer
     */
    public function create(CustomerRequest $request)
    {
        try {
            foreach ($request->input() as $key => $values) {
                if ($key == 'phones' || $key == 'faxes' || $key == 'emails') {
                    $values = array_values(array_filter($values));
                    $request->merge([$key => $values]);
                }
            }
            $customer = DB::transaction(function () use ($request) {
                $customer = new Customer;
                $customer->office_id = $request->office_id;
                $customer->name = $request->name;
                $customer->furigana = $request->furigana;
                $customer->english_name = $request->english_name;
                $customer->department = $request->department;
                $customer->position = $request->position;
                $customer->phones = $request->phones;
                $customer->faxes = $request->faxes;
                $customer->emails = $request->emails;
                $customer->note = $request->note;
                $customer->save();

                return Customer::find($customer->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $customer;
    }

    /**
     * 顧客の変更
     * @param App\Http\Requests\Address\CustomerRequest $request
     * @param App\Models\Address\Customer $customer
     * @return App\Models\Address\Customer
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        try {
            foreach ($request->input() as $key => $values) {
                if ($key == 'phones' || $key == 'faxes' || $key == 'emails') {
                    $values = array_values(array_filter($values));
                    $request->merge([$key => $values]);
                }
            }
            $customer = DB::transaction(function () use ($request, $customer) {
                $customer->office_id = $request->office_id;
                $customer->name = $request->name;
                $customer->furigana = $request->furigana;
                $customer->english_name = $request->english_name;
                $customer->department = $request->department;
                $customer->position = $request->position;
                $customer->phones = $request->phones;
                $customer->faxes = $request->faxes;
                $customer->emails = $request->emails;
                $customer->note = $request->note;
                $customer->save();

                return Customer::find($customer->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $customer;
    }

    /**
     * 顧客の削除
     * @param  App\Models\Address\Customer $customer
     * @throws \Exception
     */
    public function destroy($customer)
    {
        try {
            DB::transaction(function () use ($customer) {
                $customer->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
