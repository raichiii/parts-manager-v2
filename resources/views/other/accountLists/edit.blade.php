@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('accountList.edit',$accountList))

@section('content')
<div id="account_lists_edit">
    <account-lists-edit inline-template :props-company='@json($accountList->company)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>仕入れ先用アカウント編集</h5>
            </div>
            <div class="card-body">
                <form action="{{route('accountLists.update',[$accountList])}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="select-company">仕入れ先</label>
                        <auto-complete-component props-url="{{route('companies.search')}}" :props-query="query"
                            props-name="supplier" v-model="suggestionInput" @suggest="getResults" />
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
                        <label for="name">アカウント名</label>
                        <input id="name" type="input" class="form-control" name="name" value="{{$accountList->name}}"
                            required autofocus>
                    </div>
                    <div class=" form-group">
                        <label for="password">パスワード</label>
                        <input id="password" type="input" class="form-control" name="password"
                            value="{{$accountList->password}}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="note">備考</label>
                        <textarea id="note" class="form-control" name="note">{{$accountList->note}}</textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </account-lists-edit>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#account_lists_edit',
    })

</script>
@endsection
