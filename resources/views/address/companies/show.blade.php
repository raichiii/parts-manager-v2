@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('company', $company))

@section('content')
<!-- <div class="container"> -->
<div class="card">
    @component('components.delete_modal',['id' => $company->id, 'title' => '会社の削除', 'text' => $company->full_name,
    'action'
    => route('companies.destroy',['company' => $company])])
    @endcomponent
    <div class="card-header text-center">
        <h5>{{ $company->full_name }}</h5>
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#">会社</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('offices.index',[$company])}}">事務所</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('customers.company.index',[$company])}}">顧客</a>
            </li>
        </ul>
    </div>
    <div class=" card-body">
        <div class="col-12 text-right">
            <a href="{{route('customers.company.create',[$routeCompany])}}">
                <span class="h6 align-text-bottom text-right">
                    <i class="fas fa-plus-circle"></i>
                    顧客の新規作成
                </span>
            </a>
        </div>
        <div class="col-12 text-right">
            <a href="{{route('offices.create',[$company])}}">
                <span class="h6 align-text-bottom text-right">
                    <i class="fas fa-plus-circle"></i>
                    事務所の新規作成
                </span>
            </a>
        </div>
        <ul>
            <li>会社名： {{ $company->full_name }}</li>
            <li>会社名(ふりがな)：{{ $company->furigana }}</li>
            <li>呼称：{{ $company->short_name }}</li>
            <li>会社名(英語表記)：{{ $company->english_name }}</li>
            <li>呼称(英語名)：{{ $company->short_english_name }}</li>
            <li>会社のURL：
                @foreach($company->urls as $url)
                {{ $url }}
                @endforeach
            </li>
            <li>備考：{{ $company->note}}</li>
        </ul>
        <div class="text-center">
            <a class="btn btn-outline-primary" href="{{route('companies.edit',[$company])}}" role="button">
                編集
            </a>
            <button class="btn btn-outline-danger" data-toggle="modal"
                data-target="#deleteConfirmModal_{{$company->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
