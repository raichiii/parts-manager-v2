@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('accountList',$accountList))

@section('content')
<div class="card">
    @component('components.delete_modal',['id' => $accountList->id, 'title' => '仕入れ先用アカウントの削除', 'text' =>
    $accountList->name,'action' => route('accountLists.destroy',[$accountList])])
    @endcomponent
    <div class="card-header text-center">
        <h5>仕入れ先情報</h5>
    </div>
    <div class="card-body">
        <ul>
            <li>仕入れ先：<a
                    href="{{route('companies.show',[$accountList->company])}}">{{ $accountList->company->short_name }}</a>
            </li>
            <li>アカウント名：{{ $accountList->name }}</li>
            <li>パスワード：{{ $accountList->password }}</li>
            <li>備考: {{ $accountList->note}}</li>
            <li>作成者: <a href="{{route('users.show',[$accountList->user])}}">{{ $accountList->user->name}}</a>
            <li>作成日: {{$accountList->created_at->format('Y/m/d') }}</li>
        </ul>
        <div class="text-center">
            <a class="btn btn-outline-primary" href="{{route('accountLists.edit',[$accountList])}}" role="button">
                編集
            </a>
            <button class="btn btn-outline-danger" data-toggle="modal"
                data-target="#deleteConfirmModal_{{$accountList->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
