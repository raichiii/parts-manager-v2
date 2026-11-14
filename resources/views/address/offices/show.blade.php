@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('office',$routeCompany,$office))

@section('content')
<!-- <div class="container"> -->
<div class="card">
    @component('components.delete_modal',['id' => $office->id, 'title' => '事務所の削除', 'text' => $office->name, 'action'
    => route('offices.destroy',['company' => $routeCompany, 'office' => $office])])
    @endcomponent
    <div class="card-header text-center">
        <h5>事務所情報</h5>
    </div>
    <div class="col-12 text-right">
        <a href="{{route('customers.create',[$routeCompany, $office])}}">
            <span class="h6 align-text-bottom text-right">
                <i class="fas fa-plus-circle"></i>
                顧客の新規作成
            </span>
        </a>
    </div>
    <div class="card-body">
        <ul>
            <li>会社名：{{ $office->company->full_name}}</li>
            <li>事務所名：{{ $office->name}}</li>
            <li>郵便番号：{{ $office->postalcode }}</li>
            <li>都道府県：{{ $office->prefecture->name }}</li>
            <li>住所：{{ $office->address}}</li>
            <li>ビル名：{{ $office->building}}</li>
            <li>電話番号：
                @foreach($office->phones as $phone)
                {{ $phone }}
                @endforeach
            </li>
            <li>Fax：
                @foreach($office->faxes as $fax)
                {{ $fax }}
                @endforeach
            </li>
            <li>メールアドレス：
                @foreach($office->emails as $email)
                {{ $email }}
                @endforeach
            </li>
            <li>備考：{{ $office->note}}</li>
        </ul>
        <div class="text-center">
            <a class="btn btn-outline-primary" href="{{route('offices.edit',[$routeCompany, $office])}}" role=" button">
                編集
            </a>
            <button class="btn btn-outline-danger" data-toggle="modal"
                data-target="#deleteConfirmModal_{{$office->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
