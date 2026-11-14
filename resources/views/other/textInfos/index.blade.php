@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('textInfos.index'))

@section('content')
<div id="text_infos_index">
    <text-infos-index props-url="{{ route('textInfos.get') }}" inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>テキスト情報検索</h5>
            </div>
            <div class="card-body">
                <label for="and-search" class="mr-1" style="margin-bottom:0px;">
                    <input type="radio" id="and-search" v-model.number="andOr" value="1"  @change="fetch()" />
                    AND
                </label>
                <label for="or-search" class="mr-1" style="margin-bottom:0px;">
                    <input type="radio" id="or-search" v-model.number="andOr" value="0"  @change="fetch()" />
                    OR
                </label>
                <input id="search-column" class="form-control mb-1" placeholder="ランディスク内のテキストファイルを検索"
                    v-model="suggestionInput" @input="debouncedFetch()" ref="focus" />
                <div class="panel-footer row" v-if="results.length" v-cloak>
                    <span v-for="(row, idx) in results" :key="row.id" class="list-group-item col-md-12">
                        <p class="h4" v-text="row.title"></p>
                        <p v-html="row.content"></p>
                    </span>
                </div>
                <p class="text-center" v-else>該当するレコードが見つかりません</p>
            </div>
        </div>
    </text-infos-index>
</div>
@endsection

@section('js')
<script>
    let vm = new Vue({
        el: "#text_infos_index",
    })
</script>
@endsection
