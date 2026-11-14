@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('detailedBoards'))

@section('content')
<div id="detailed_boards_index">
    <detailed-boards-index inline-template :props-hard='@json(BOARD_HARD_STATE_ARRAY)' :props-adjust='@json(BOARD_ADJUST_STATE_ARRAY)' :props-ship='@json(BOARD_SHIP_STATE_ARRAY)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>在庫基板の詳細記録</h5>
            </div>
            <div class="col-12 text-right">
                <a href="{{route('detailedBoards.create')}}">
                    <span class="h6 align-text-bottom text-right">
                        <i class="fas fa-plus-circle"></i>
                        在庫基板の新規登録
                    </span>
                </a>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="control-label col-md-2">H/W動作</label>
                    <div class="col-md-10">
                        @foreach(BOARD_HARD_STATE_ARRAY as $key => $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="search-hardware-{{$value}}" type="radio"
                                name="hardware_operation" value="{{$key}}" v-model="checkedHardClassifications"
                                style="width: 30px; height: 15px;">
                            <label class="form-check-label" for="search-hardware-{{$value}}">
                                {{$value}}</label>
                        </div>
                        @endforeach
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="search-hardware-all" type="radio"
                                name="hardware_operation" value="all" v-model="checkedHardClassifications"
                                style="width: 30px; height: 15px;">
                            <label class="form-check-label" for="search-hardware-all">
                                全て</label>
                        </div>
                    </div>
                    <label class="control-label col-md-2">調整校正</label>
                    <div class="col-md-10">
                        @foreach(BOARD_ADJUST_STATE_ARRAY as $key => $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="search-adjustment-{{$value}}" type="radio"
                                name="adjustment_calibration" value="{{$key}}" v-model="checkedAdjustmentClassifications"
                                style="width: 30px; height: 15px;">
                            <label class="form-check-label" for="search-adjustment-{{$value}}">
                                {{$value}}</label>
                        </div>
                        @endforeach
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="search-adjustment-all" type="radio"
                                name="adjustment_calibration" value="all" v-model="checkedAdjustmentClassifications"
                                style="width: 30px; height: 15px;">
                            <label class="form-check-label" for="search-adjustment-all">
                                全て</label>
                        </div>
                    </div>
                    <label class="control-label col-md-2">出荷</label>
                    <div class="col-md-10">
                        @foreach(BOARD_SHIP_STATE_ARRAY as $key => $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="search-shipment-{{$value}}" type="radio"
                                name="shipment" value="{{$key}}" v-model="checkedShipmentClassifications"
                                style="width: 30px; height: 15px;">
                            <label class="form-check-label" for="search-shipment-{{$value}}">
                                {{$value}}</label>
                        </div>
                        @endforeach
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="search-shipment-not-shipped" type="radio"
                                name="shipment" value="notShipped" v-model="checkedShipmentClassifications"
                                style="width: 30px; height: 15px;">
                            <label class="form-check-label" for="search-shipment-not-shipped">
                                していない+社内用</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="searc-shipment-all" type="radio"
                                name="shipment" value="all" v-model="checkedShipmentClassifications"
                                style="width: 30px; height: 15px;">
                            <label class="form-check-label" for="search-shipment-all">
                                全て</label>
                        </div>
                    </div>
                </div>
                <bootstrap-table-component ref="table" :props-data="{{$detailedBoards}}" :props-columns="columns"/>
            </div>
        </div>
    </detailed-boards-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#detailed_boards_index',
    })

</script>
@endsection
