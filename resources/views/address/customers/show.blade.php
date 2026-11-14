@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('customer',$routeCompany,$routeOffice,$customer))

@section('content')
<!-- <div class="container"> -->
<div class="card">
    @component('components.delete_modal',['id' => $customer->id, 'title' => '顧客の削除', 'text' => $customer->name, 'action'
    => route('customers.destroy',['company' => $routeCompany, 'office' => $routeOffice, 'customer' => $customer])])
    @endcomponent
    <div class="card-header text-center">
        <h5>顧客情報</h5>
    </div>
    <div class="card-body">
        <ul>
            <li>会社名：{{ $routeCompany->full_name}}</li>
            <li>事務所名：{{ $routeOffice->name}}</li>
            <li>顧客名：{{ $customer->name}}</li>
            <li>顧客名(ふりがな)：{{ $customer->furigana}}</li>
            <li>顧客名(英語表記)：{{ $customer->english_name}}</li>
            <li>部署：{{ $customer->department}}</li>
            <li>肩書き：{{ $customer->position}}</li>
            <li>電話番号：
                @foreach($customer->phones as $phone)
                {{ $phone }}
                @endforeach
            </li>
            <li>Fax：
                @foreach($customer->faxes as $fax)
                {{ $fax }}
                @endforeach
            </li>
            <li>メールアドレス：
                @foreach($customer->emails as $email)
                {{ $email }}
                @endforeach
            </li>
            <li>備考：{{ $customer->note}}</li>
        </ul>
        <div class="text-center">
            <a class="btn btn-outline-primary"
                href="{{route('customers.edit',[$routeCompany, $routeOffice, $customer])}}" role=" button">
                編集
            </a>
            <button class="btn btn-outline-danger" data-toggle="modal"
                data-target="#deleteConfirmModal_{{$customer->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
