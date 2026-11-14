@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('detailedBoard.edit', $detailedBoard))

@section('content')
<!-- <div class="container"> -->
<div id="detailed_boards_edit">
    <detailed-boards-edit inline-template :props-detailed-board='@json($detailedBoard)'
        :props-versions='@json($versions)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>在庫基板の詳細記録の編集</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('detailedBoards.update',[$detailedBoard]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <h5>基板検索フォーム</h5>
                        <auto-complete-component props-url="{{route('printCircuitBoards.search')}}"
                            props-name="print_circuit_board" v-model="suggestionInput" @suggest="getResults" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.length && open">
                            <ul class="list-group">
                                <li v-for="(result, index) in results" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action" @click="suggestionClick(index)"
                                    v-text="suggestionList(index)">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div v-if="versions.length > 0">
                        <div class="form-group">
                            <label for="version">バージョン</label>
                            <select id="version" class="custom-select" name="version">
                                <option v-for="version in versions" :key="version.id" :value="version.version"
                                    :selected="version.initial == true">
                                    @{{version.version}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="hardware-operation">H/W動作</label>
                            <div class="col-md-12">
                                @foreach(BOARD_HARD_STATE_ARRAY as $key => $value)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="detailed-hardware-{{$value}}" type="radio"
                                        name="hardware_operation" value="{{$key}}" style="width: 30px; height: 15px;"
                                        {{$detailedBoard->hardware_operation == $key ? "checked" : ''}}>
                                    <label class="form-check-label" for="detailed-hardware-{{$value}}">
                                        {{$value}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="adjustment-calibration">調整校正</label>
                            <div class="col-md-12">
                                @foreach(BOARD_ADJUST_STATE_ARRAY as $key => $value)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="detailed-adjustment-{{$value}}" type="radio"
                                        name="adjustment_calibration" value="{{$key}}"
                                        style="width: 30px; height: 15px;"
                                        {{$detailedBoard->adjustment_calibration == $key ? "checked" : ''}}>
                                    <label class="form-check-label" for="detailed-adjustment-{{$value}}">
                                        {{$value}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="shipment">出荷</label>
                            <div class="col-md-12">
                                @foreach(BOARD_SHIP_STATE_ARRAY as $key => $value)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="detailed-shipment-{{$value}}" type="radio"
                                        name="shipment" value="{{$key}}" style="width: 30px; height: 15px;"
                                        {{$detailedBoard->shipment == $key ? "checked" : ''}}>
                                    <label class="form-check-label" for="detailed-shipment-{{$value}}">
                                        {{$value}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="software-version">ソフトウェア・バージョン</label>
                            <input id="software-version" type="text" class="form-control" name="software_version"
                                value="{{$detailedBoard->software_version}}" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="check-sum">チェックサム</label>
                            <input id="check-sum" type="text" class="form-control" name="check_sum"
                                value="{{$detailedBoard->check_sum}}" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="production-date">製造日</label>
                            <input id="production-date" type="date" class="form-control" name="production_date"
                                value="{{$detailedBoard->production_date}}" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="lot-number">LOT NO</label>
                            <input :class="errorClassObject('lotNumber')" v-model="edit.lotNumber" id="lot-number"
                                type="text" class="form-control" name="lot_amount" autofocus
                                value="{{$detailedBoard->lot_amount}}">
                            <div class="invalid-feedback">
                                英数字で入力してください
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="user-id">初期担当</label>
                            <select id="user-id" class="custom-select" name="user_id">
                                @foreach($users as $user)
                                <option value="{{$user->id}}" @if ($user->id === $detailedBoard->user_id)
                                    selected>
                                    @else
                                    >
                                    @endif
                                    {{$user->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="depository">保管場所</label>
                            <input id="depository" type="text" class="form-control" name="depository"
                                value="{{$detailedBoard->depository}}" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="note">備考</label>
                            <textarea id="note" class="form-control" name="note">{{$detailedBoard->note}}</textarea>
                        </div>
                        <div class="text-center">
                            <button :disabled="isValid == false" type="submit" name="submit"
                                class="btn btn-primary">変更</button>
                        </div>
                    </div>
                    <div v-else-if="versions.length == 0 && suggestionInput.length > 0">
                        基板が登録されていません
                    </div>
                </form>
            </div>
        </div>
    </detailed-boards-edit>
</div>
<!-- </div> -->
@endsection

@section('js')
<script>
    new Vue({
        el: '#detailed_boards_edit'
    })

</script>
@endsection
