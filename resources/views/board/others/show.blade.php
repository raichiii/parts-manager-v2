@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('other', $other))

@section('content')
<!-- <div class="container"> -->
@component('components.delete_modal',['id' => $other->id, 'title' => 'その他の削除', 'text' =>
$other->name, 'action' => route('others.destroy',[$other])])
@endcomponent
<div class="card">
    <div class="card-header text-center">
        <h5>その他情報</h5>
    </div>
    <div class="card-body">
        <ul class="h3">
            <li>メーカー名：
                <a href="{{route('companies.show',[$other->maker])}}">
                    {{$other->maker->full_name}}
                </a>
            </li>
            <li>型番：{{ $other->thing->model_no}}</li>
            <li>部品名：{{ $other->name }}</li>
            <li>略名：{{ $other->short_name }}</li>
            <li>英語名：{{ $other->english_name }}</li>
            <li>備考：{{ $other->note }}</li>
        </ul>
        <div class="btn-toolbar w-100">
            <a class="btn btn-outline-primary col-md-9" href="{{route('others.edit',[$other])}}" role="button">
                編集
            </a>
            <button class="btn btn-outline-danger col-md-3" data-toggle="modal" data-target="#deleteConfirmModal_{{$other->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
