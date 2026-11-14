@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('detailedBoard.create'))

@section('content')
<div id="detailed_boards_create">
    <detailed-boards-create inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>在庫基板の詳細記録の作成</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('detailedBoards.store') }}" method="post">
                    @csrf
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
                                <option v-for="version in versions" :key="version.id" :value="version.version">
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
                                        @if($key=="NG" ) checked @endif>
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
                                    <input :class="errorClassObject('adjustmentCalibration')" class="form-check-input"
                                        id="detailed-adjustment-{{$value}}" type="radio" name="adjustment_calibration"
                                        value="{{$key}}" style="width: 30px; height: 15px;" @if($key=="NG" ) checked
                                        @endif>
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
                                    <input class="form-check-input" id="detailedShipment{{$value}}" type="radio"
                                        name="shipment" value="{{$key}}" style="width: 30px; height: 15px;"
                                        @if($key=="Done" ) checked @endif>
                                    <label class="form-check-label" for="detailedShipment{{$value}}">
                                        {{$value}}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="invalid-feedback">
                                いずれかを選択してください
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="software-version">ソフトウェア・バージョン</label>
                            <input id="software-version" type="text" class="form-control" name="software_version"
                                autofocus>
                        </div>
                        <div class="form-group">
                            <label for="check-sum">チェックサム</label>
                            <input id="check-sum" type="text" class="form-control" name="check_sum" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="production-date">製造日</label>
                            <input id="production-date" type="date" class="form-control" name="production_date"
                                value="{{$today}}" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="lot-number">LOT NO</label>
                            <input :class="errorClassObject('lotNumber')" v-model="create.lotNumber" id="lot-number"
                                type="text" class="form-control" name="lot_amount" autofocus>
                            <div class="invalid-feedback">
                                数字で入力してください
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="user-id">初期担当</label>
                            <select id="user-id" class="custom-select" name="user_id">
                                @foreach($users as $user)
                                <option value="{{$user->id}}" @if ($user->id === auth()->id()) selected>
                                    @else>
                                    @endif
                                    {{$user->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="depository">保管場所</label>
                            <input id="depository" type="text" class="form-control" name="depository" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="note">備考</label>
                            <textarea id="note" class="form-control" name="note"></textarea>
                        </div>
                        <div class="text-center">
                            <button :disabled="isValid == false" type="submit" name="submit"
                                class="btn btn-primary">作成</button>
                        </div>
                    </div>
                    <div v-else-if="versions.length == 0 && suggestionInput.length > 0">
                        基板が登録されていません
                    </div>
                </form>
            </div>
        </div>
    </detailed-boards-create>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#detailed_boards_create'
    })

</script>
@endsection
