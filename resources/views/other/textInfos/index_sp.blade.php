@extends('layouts.app_sp')

@section('breadcrumbs', Breadcrumbs::render('detailedBoards.kensaku'))

@section('content')
<div id="detailed_boards_kensaku">
    <detailed-boards-kensaku :props-col-names="{{ $colNames }}" props-url="{{ route('detailedBoards.get') }}"
        props-search-url="{{ $searchUrl }}" inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>在庫基板詳細記録</h5>
            </div>
            <div class="card-body">
                <label for="rule0" class="mr-1" style="margin-bottom:0px;"><input type="radio" id="rule0" v-model="rule" value="0"  @change="fetch()" />在P番号</label>
                <label for="rule1" class="mr-1" style="margin-bottom:0px;"><input type="radio" id="rule1" v-model="rule" value="1"  @change="fetch()" />基板名</label>
                <label for="rule2" class="mr-1" style="margin-bottom:0px;"><input type="radio" id="rule2" v-model="rule" value="2"  @change="fetch()" />全体</label>
                <input id="search-column" class="form-control mb-1" placeholder="在庫基板で検索" v-model="suggestionInput" @input="debouncedFetch()" ref="focus" />
                <div class="panel-footer row" v-if="results.length" v-cloak>
                    <span v-for="(row, idx) in results" :key="row.id"
                        class="list-group-item col-md-4">
                        <div class="circle-id" v-text="`在P-${row.id}`"></div>
                        <h5 v-text="row.modelNo" class="font-weight-bold" @click="clickPart($event)" style="display:inline-block;margin-bottom:0px;"></h5>
                        <p v-text="row.createdAt" style="display:inline-block;margin-bottom:0px;"></p>
                        <div class="list-group">
                            <div v-for="info in row.infos" v-html="info"></div>
                        </div>
                        <div class="outline-note" v-html="row.note"></div>
                    </span>
                </div>
                <p class="text-center" v-else>該当するレコードが見つかりません</p>
            </div>
        </div>
    </detailed-boards-kensaku>
</div>
@endsection

@section('js')
<script>
    let vm = new Vue({
        el: "#detailed_boards_kensaku",
    })
</script>
@endsection
