@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('other.edit', $other))

@section('content')
<div id="others_edit">
    <others-edit inline-template :props-maker='@json($other->maker)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>その他の編集</h5>
            </div>
            <div class="card-body">

                <form action="{{route('others.update',[$other])}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="select-company">メーカー</label>
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
                        <input id="model-no" type="input" class="form-control" name="model_no"
                            value="{{$other->thing->model_no}}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="name">部品名</label>
                        <input id="name" type="input" class="form-control" name="name" value="{{$other->name}}">
                    </div>
                    <div class="form-group">
                        <label for="short-name">略名</label>
                        <input id="short-name" type="input" class="form-control" name="short_name"
                            value="{{$other->short_name}}">
                    </div>
                    <div class="form-group">
                        <label for="english-name">英語名</label>
                        <input id="english-name" type="input" class="form-control" name="english_name"
                            value="{{$other->english_name}}">
                    </div>
                    <div class="form-group">
                        <label for="note">備考</label>
                        <input id="note" type="text" class="form-control" name="note"
                            value="{{$other->note}}" autofocus>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">変更</button>
                    </div>
                </form>
            </div>
        </div>
    </others-edit>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#others_edit',
    })

</script>
@endsection
