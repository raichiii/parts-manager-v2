@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('part.create'))

@section('content')
<div id="parts_create">
    <parts-create inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>部品の登録</h5>
            </div>
            <div class="card-body">

                <form action="{{route('parts.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="maker" class="text-daner">メーカー</label>
                        <auto-complete-component props-url="{{route('companies.search')}}" :props-query="query"
                            props-name="maker" v-model="suggestionInput" @suggest="getResults" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.length && open">
                            <ul class="list-group">
                                <li v-for="(result, index) in results" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action" @click="suggestionClick(index)"
                                    v-text="suggestionList(index)"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="model-no">型番</label>
                        <input id="model-no" type="input" class="form-control" name="model_no" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="name">部品名</label>
                        <input id="name" type="input" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="short-name">略名</label>
                        <input id="short-name" type="input" class="form-control" name="short_name">
                    </div>
                    <div class="form-group">
                        <label for="english-name">英語名</label>
                        <input id="english-name" type="input" class="form-control" name="english_name">
                    </div>
                    <div class="form-group">
                        <label for="pno">PNO</label>
                        <input id="pno" type="text" class="form-control" name="pno">
                    </div>
                    <div class="form-group">
                        <label for="p">P</label>
                        <input id="p" type="text" class="form-control" name="p">
                    </div>
                    <div class="form-group">
                        <label for="common">共通</label>
                        <input id="common" type="text" class="form-control" name="common">
                    </div>
                    <div class="form-group">
                        <label for="classification">分類</label>
                        <input id="classification" type="text" class="form-control" name="classification">
                    </div>
                    <div class="form-group">
                        <label for="status">ステータス</label>
                        <input id="status" type="text" class="form-control" name="status">
                    </div>
                    <div class="form-group">
                        <label for="interchange">互換</label>
                        <input id="interchange" type="text" class="form-control" name="interchange">
                    </div>
                    <div class="form-group">
                        <label for="alternative">代替</label>
                        <input id="alternative" type="text" class="form-control" name="alternative">
                    </div>
                    <div class="form-group">
                        <label for="note">備考</label>
                        <input id="note" type="text" class="form-control" name="note">
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">変更</button>
                    </div>
                </form>
            </div>
        </div>
    </parts-create>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#parts_create',
    })

</script>
@endsection
