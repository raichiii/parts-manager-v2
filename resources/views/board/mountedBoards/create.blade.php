@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('mountedBoard.create', $routePrintCircuitBoard))

@section('content')
<div id="mounted_boards_create">
    <mounted-boards-create inline-template :props-mounted-boards='@json($latestMountedBoards)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>基板の登録</h5>
            </div>
            <div class="card-body">
                <form action="{{route('mountedBoards.store',[$routePrintCircuitBoard])}}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    {{-- <div class="form-group">
                        <label for="print-circuit-board">生基板</label>
                        <select id="print-circuit-board" class="custom-select" name="print_circuit_board_id">
                            <option value="{{$routePrintCircuitBoard->id}}" selected>
                                {{$routePrintCircuitBoard->name}}
                            </option>
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <input type="hidden" name="print_circuit_board_id" value="{{ $routePrintCircuitBoard->id }}">
                        <input type="hidden" name="version" value="1">
                    </div>
                    <div class="form-group">
                        <label for="version-name-select">新規または既存</label>
                        <select id="version-name-select" v-model="selectedVersionType" class="custom-select">
                            <option value="new" selected>新規</option>
                            <option v-if="mountedBoards.length > 0" value="old">既存</option>
                        </select>
                    </div>
                    <div v-if="selectedVersionType == 'new'" class="form-group">
                        <label for="version-name">新規バージョン名</label>
                        <input id="version-name" type="text" class="form-control" name="version_name">
                    </div>
                    <div v-else class="form-group">
                        <label for="version-name">既存バージョン名の選択</label>
                        <select id="version-name" class="custom-select" name="version_name">
                            <option v-for="mountedBoard in mountedBoards" :key="mountedBoard.id"
                                :value="mountedBoard.versionName">
                                @{{mountedBoard.versionName == null ? 'なし' : mountedBoard.versionName}}
                                @{{'(次バージョン : ' + (mountedBoard.version+1) + ')'}}
                            </option>
                        </select>
                    </div>
                    CSVファイルで部品をインポート
                    <div class="input-group mb-2">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="csv_file" accept=".csv">
                            <label class="custom-file-label" for="customFile" data-browse="参照">ファイル選択...</label>
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary reset">取消</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">詳細</label>
                        <input id="description" type="text" class="form-control" name="description" autofocus>
                    </div>
                    <hr>
                    <div class="col-sm-12 m-3">
                        <h5>部品リスト</h5>
                        <auto-complete-component props-url="{{route('parts.search')}}" v-model="suggestionInput"
                            @suggest="getResults" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.length && open">
                            <ul class="list-group">
                                <li v-for="(result, index) in results" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action" @click="suggestionClick(index)"
                                    v-text="suggestionList(index)"></li>
                            </ul>
                        </div>
                    </div>
                    <table class="table" v-show="create.parts.length > 0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 25%">参照番号</th>
                                <th scope="col" style="width: 25%">メーカー</th>
                                <th scope="col" style="width: 25%">部品名</th>
                                <th scope="col" style="width: 10%">実装済</th>
                                <th scope="col" style="width: 15%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(part, index) in create.parts">
                                <td>
                                    <input v-model="part.referenceNumber" name="reference_number[]" class="form-control"
                                        type="text" autofocus>
                                </td>
                                <td>@{{part.maker.name}}</td>
                                <td>
                                    @{{part.name + '(' + part.model_no + ')'}}
                                    <div class="input-group">
                                        <input v-model="part.id" type="hidden" class="form-control" name="parts[]"
                                            autofocus readonly>
                                    </div>
                                </td>
                                <td nowrap>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="0" v-model="part.isMounted"
                                            :name="getIsMountedName(index)" :id="'radio1_' + index">
                                        <label :class="{'text-danger': part.isMounted == 0}" class="form-check-label"
                                            :for="'radio1_' + index">未実装</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="1" v-model="part.isMounted"
                                            :name="getIsMountedName(index)" :id="'radio2_' + index">
                                        <label :class="{'text-success': part.isMounted == 1}" class="form-check-label"
                                            :for="'radio2_' + index">実装済</label>
                                    </div>
                                </td>
                                <td><button type="button" class="btn btn-outline-primary"
                                        @click.prevent="copyInputForParts(index)">
                                        コピー
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                        @click.prevent="deleteInputForParts(index)">
                                        削除
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">作成</button>
                    </div>
                </form>
            </div>
        </div>
    </mounted-boards-create>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#mounted_boards_create',
    })
    $('.custom-file-input').on('change', function () {
        $(this).next('.custom-file-label').html($(this)[0].files[0].name);
    });
    //ファイルの取消
    $('.reset').click(function () {
        $(this).parent().prev().children('.custom-file-label').html('ファイル選択...');
        $('.custom-file-input').val('');
    });

</script>
@endsection

@section('css')
<style>
    .custom-file {
        overflow: hidden;
    }

    .custom-file-label {
        white-space: nowrap;
    }

</style>
@endsection
