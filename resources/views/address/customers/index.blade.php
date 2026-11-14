@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('customers',$routeCompany))

@section('content')

<div class="card">
    <div class="card-header text-center">
        <h5>{{$routeCompany->full_name}}</h5>
        @isset($routeOffice)
        @else
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{route('companies.show',[$routeCompany])}}">会社</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('offices.index',[$routeCompany])}}">事務所</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="">顧客</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="{{route('companies.requestedLists',[$routeCompany])}}">注文依頼リスト</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('companies.orderedLists',[$routeCompany])}}">発注済みリスト</a>
            </li> --}}
        </ul>
        @endisset
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
                        <th style="width: 30%">事務所名</th>
                        <th style="width: 30%">顧客名</th>
                        <th style="width: 20%">肩書き</th>
                        <th style="width: 20%;text-align: center">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr class="">
                        <td>{{$customer->office->name}}</td>
                        <td>{{$customer->name}}</td>
                        <td>{{$customer->position}}</td>
                        <td nowrap style="text-align: center">
                            <a class="btn btn-outline-primary"
                                href="{{route('customers.show',[$routeCompany, $customer->office, $customer])}}"
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
