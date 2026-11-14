@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('individual',$individual))

@section('content')
<!-- <div class="container"> -->
<div class="card">
    @component('components.delete_modal',['id' => $individual->id, 'title' => '個人の削除', 'text' => $individual->name,
    'action'
    => route('individuals.destroy',['individual' => $individual])])
    @endcomponent
    <div class="card-header text-center">
        <h5>個人情報</h5>
    </div>
    <div class="card-body">
        <ul>
            <li>名前：{{ $individual->name }}</li>
            <li>名前(ふりがな)：{{ $individual->furigana }}</li>
            <li>名前(英語名)：{{ $individual->english_name }}</li>
            <li>郵便番号：{{ $individual->postalcode }}</li>
            <li>都道府県：{{ $individual->prefecture->name}}</li>
            <li>住所：{{ $individual->address }}</li>
            <li>電話番号：
                @foreach($individual->phones as $phone)
                {{ $phone }}
                @endforeach
            </li>
            <li>Fax：
                @foreach($individual->faxes as $fax)
                {{ $fax }}
                @endforeach
            </li>
            <li>メールアドレス：
                @foreach($individual->emails as $email)
                {{ $email }}
                @endforeach
            </li>
            <li>URL：
                @foreach($individual->urls as $url)
                {{ $url }}
                @endforeach
            </li>
            <li>備考：{{ $individual->note}}</li>
        </ul>
        <div class="text-center">
            <a class="btn btn-outline-primary" href="{{route('individuals.edit',['individual' => $individual])}}"
                role=" button">
                編集
            </a>
            <button class="btn btn-outline-danger" data-toggle="modal"
                data-target="#deleteConfirmModal_{{$individual->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
