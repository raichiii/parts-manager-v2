@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('part', $part))

@section('content')
<!-- <div class="container"> -->
@component('components.delete_modal',['id' => $part->id, 'title' => '部品の削除', 'text' =>
$part->name, 'action' => route('parts.destroy',[$part])])
@endcomponent
<div class="card">
    <div class="card-header text-center">
        <h5>部品情報</h5>
    </div>
    <div class="card-body">
        <ul class="h3">
            <li>メーカー名：
                <a href="{{route('companies.show',[$part->maker])}}">
                    {{$part->maker->full_name}}
                </a>
            </li>
            <li>型番：{{ $part->thing->model_no}}</li>
            <li>部品名：{{ $part->name }}</li>
            <li>略名：{{ $part->short_name }}</li>
            <li>英語名：{{ $part->english_name }}</li>
            <li>PNO：{{ $part->pno }}</li>
            <li>P：{{ $part->p }}</li>
            <li>共通：{{ $part->common }}</li>
            <li>分離：{{ $part->classification }}</li>
            <li>ステータス：{{ $part->status }}</li>
            <li>互換：{{ $part->interchange }}</li>
            <li>代替：{{ $part->alternative }}</li>
            <li>備考：{{ $part->note }}</li>
        </ul>
        <div class="btn-toolbar w-100">
            <a class="btn btn-outline-primary col-md-9" href="{{route('parts.edit',[$part])}}" role="button">
                編集
            </a>
            <button class="btn btn-outline-danger col-md-3" data-toggle="modal" data-target="#deleteConfirmModal_{{$part->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
