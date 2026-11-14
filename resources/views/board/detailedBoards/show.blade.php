@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('detailedBoard', $detailedBoard))

@section('content')
<div class="card">
    @component('components.delete_modal',['id' => $detailedBoard->id, 'title' => '在庫基板詳細記録の削除', 'text' =>
    $detailedBoard->mountedBoard->printCircuitBoard->name, 'action'
    => route('detailedBoards.destroy',['detailed_board' => $detailedBoard])])
    @endcomponent
    <div class="card-header text-center">
        <h5>{{$detailedBoard->mountedBoard->printCircuitBoard->name}}</h5>
    </div>
    <div class=" card-body">
        <div class="col-12 text-right">
            <a href="{{route('detailedBoards.create')}}">
                <span class="h6 align-text-bottom text-right">
                    <i class="fas fa-plus-circle"></i>
                    在庫基板の新規登録
                </span>
            </a>
        </div>
        <ul>
            <li>ID： 在P-{{ $detailedBoard->id}}</li>
            <li>基板名： {{ $detailedBoard->mountedBoard->printCircuitBoard->thing->model_no }}</li>
            <li>バージョン: {{$detailedBoard->mountedBoard->version}}</li>
            <li>HW動作： {{ BOARD_HARD_STATE_ARRAY[$detailedBoard->hardware_operation] }}</li>
            <li>調整校正： {{ BOARD_ADJUST_STATE_ARRAY[$detailedBoard->adjustment_calibration] }}</li>
            <li>出荷： {{ BOARD_SHIP_STATE_ARRAY[$detailedBoard->shipment] }}</li>
            <li>ソフトウェア・バージョン： {{ $detailedBoard->software_version }}</li>
            <li>チェックサム： {{ $detailedBoard->check_sum }}</li>
            <li>Lot_no： {{ $detailedBoard->lot_amount }}</li>
            <li>製造日： {{ $detailedBoard->production_date }}</li>
            <li>担当者： {{ $detailedBoard->user->name }}</li>
            <li>保管場所： {{ $detailedBoard->depository}}</li>
            <li>備考：{{ $detailedBoard->note}}</li>
        </ul>
        <div class="text-center">
            <a class="btn btn-outline-primary" href="{{route('detailedBoards.edit',[$detailedBoard])}}" role="button">
                編集
            </a>
            <button class="btn btn-outline-danger" data-toggle="modal"
                data-target="#deleteConfirmModal_{{$detailedBoard->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
