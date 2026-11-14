@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('offices',$routeCompany))

@section('content')

<div class="card">
    <div class="card-header text-center">
        <h5>{{$routeCompany->full_name}}</h5>
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{route('companies.show',[$routeCompany])}}">会社</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="">事務所</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('customers.company.index',[$routeCompany])}}">顧客</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="{{route('companies.requestedLists',[$routeCompany])}}">注文依頼リスト</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('companies.orderedLists',[$routeCompany])}}">発注済みリスト</a>
            </li> --}}
        </ul>
    </div>
    <div class="card-body">
        <div class="col-12 text-right">
            <a href="{{route('customers.company.create',[$routeCompany])}}">
                <span class="h6 align-text-bottom text-right">
                    <i class="fas fa-plus-circle"></i>
                    顧客の新規作成
                </span>
            </a>
        </div>
        <div class="col-12 text-right">
            <a href="{{route('offices.create',[$routeCompany])}}">
                <span class="h6 align-text-bottom text-right">
                    <i class="fas fa-plus-circle"></i>
                    事務所の新規作成
                </span>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead class="border-bottom border-danger">
                    <tr>
                        <th style="width: 40%">事務所名</th>
                        <th style="width: 20%">郵便番号</th>
                        <th style="width: 20%">住所</th>
                        <th style="width: 20%;text-align: center">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offices as $office)
                    <tr class="">
                        <td>{{$office->name}}</td>
                        <td>{{$office->postalcode}}</td>
                        <td>{{$office->address}}</td>
                        <td nowrap style="text-align: center">
                            <a class="btn btn-outline-primary" href="{{route('offices.show',[$routeCompany, $office])}}"
                                role="button">
                                詳細
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
