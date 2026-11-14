@extends('layouts.app_sp')

@section('content')
<div id="parts_kensaku">
    <parts-kensaku props-url="{{ route('printCircuitBoards.landisk_job_dir') }}"
    props-get-url="{{ route('parts.get') }}" props-search-url="{{ $searchUrl }}" inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>部品実装検索</h5>
            </div>
            <div class="card-body">
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
