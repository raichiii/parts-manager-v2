@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('accountList.create'))

@section('content')
<div id="account_lists_create">
    <account-lists-create inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>仕入れ先用アカウント登録</h5>
            </div>
            <div class="card-body">
                <form action="{{route('accountLists.store')}}" method="post">
                    @csrf
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
                        <input id="name" type="input" class="form-control" name="name" required autofocus>
                    </div>
                    <div class=" form-group">
                        <label for="password">パスワード</label>
                        <input id="password" type="input" class="form-control" name="password" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="note">備考</label>
                        <textarea id="note" class="form-control" name="note"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">作成</button>
                    </div>
                </form>
            </div>
        </div>
    </account-lists-create>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#account_lists_create',
    })

</script>
@endsection
