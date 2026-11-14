@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('parts.kensaku'))

@section('content')
<div id="parts_kensaku">
    <parts-kensaku props-url="{{ route('printCircuitBoards.landisk_job_dir') }}"
    props-get-url="{{ route('parts.get') }}" props-search-url="{{ $searchUrl }}" inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>部品実装検索</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div class="float-right">
                        <div class="row">
                            <file-upload-component props-file-name="file-buhin-list" props-dialog-name="部品マスタ変更"
                            props-url="{{ route('parts.changeCsv') }}" :props-csrf="{{ json_encode(csrf_token()) }}"></file-upload-component>
                            <file-upload-component props-file-name="file-board-job" props-dialog-name="基板マスタ変更"
                            props-url="{{ route('parts.changeCsv') }}" :props-csrf="{{ json_encode(csrf_token()) }}"></file-upload-component>
                            <div class="export btn-group">
                                <button class="btn btn-secondary dropdown-toggle" aria-label="Export data" data-toggle="dropdown" type="button" title="Export data" aria-expanded="false">
                                    <svg class="svg-inline--fa fa-download fa-w-16" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="download" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                        <path fill="currentColor" d="M216 0h80c13.3 0 24 10.7 24 24v168h87.7c17.8 0 26.7 21.5 14.1 34.1L269.7 378.3c-7.5 7.5-19.8 7.5-27.3 0L90.1 226.1c-12.6-12.6-3.7-34.1 14.1-34.1H192V24c0-13.3 10.7-24 24-24zm296 376v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h146.7l49 49c20.1 20.1 52.5 20.1 72.6 0l49-49H488c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z">
                                        </path>
                                    </svg>
                                    <!-- <i class="fa fa-download"></i> Font Awesome fontawesome.com -->
                                    <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="">
                                    <a class="dropdown-item" href="{{ route('parts.downloadCsv') }}">部品マスタDL</a>
                                    <a class="dropdown-item" href="{{ route('printCircuitBoards.downloadCsv') }}">基板マスタDL</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="checkbox" id="check-all-p" v-model="checkAllP" @change="toggleCheckAllP()" /><label for="check-all-p" class="mr-2" style="margin-bottom:0px;">全選択</label>
                <span v-for="P in PSet">
                    <input type="checkbox" :id="`p-${P}`" :value="P" v-model="checkedPs" @change="toggleCheckP()" /><label :for="`p-${P}`" class="mr-1" style="margin-bottom:0px;" v-text="P? P : '無し'"></label>
                </span>
                <input id="search-column" class="form-control mb-1" placeholder="部品名やP番号で検索" v-model="suggestionInput" @input="debouncedFetch()" ref="focus" />
                <div class="panel-footer row" v-if="results.length" v-cloak>
                    <span v-for="(part, idx) in results" :key="part.model_no"
                    class="list-group-item col-md-4">
                        <h5 v-text="part.model_no" class="font-weight-bold" style="margin-bottom:0px;" @click="clickPart($event)"></h5>
                        <div v-for="info in part.infos" v-html="info"></div>
                        <table class="table table-bordered" style="width: 10rem; font-size: 22px">
                            <tbody>
                                <tr v-for="board in part.boards">
                                    <td class="table-board" @click="clipLandiskJob($event)" v-text="board[0]"></td>
                                    <td v-text="board[1]"></td>
                                </tr>
                            </tbody>
                        </table>
                    </span>
                </div>
                <p class="text-center" v-else>該当するレコードが見つかりません</p>
            </div>
        </div>
    </parts-kensaku>
</div>
@endsection

@section('js')
<script>
    let vm = new Vue({
        el: "#parts_kensaku",
    })
</script>
@endsection
