@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('printCircuitBoard.create'))

@section('content')
<div id="print_circuit_boards_create">
    <print-circuit-boards-create inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>生基板の登録</h5>
            </div>
            <div class="card-body">
                <form action="{{route('printCircuitBoards.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="select-maker">メーカー</label>
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
                        <label for="model_no">基板名（型番）</label>
                        <input id="model_no" type="text" class="form-control" name="model_no" required>
                    </div>
                    <div class="form-group">
                        <label for="valid-years">保管期間</label>
                        <select id="valid-years" class="custom-select" name="valid_years">
                            <option value="-1">永久</option>
                            <option value="0">廃業</option>
                            @for($i = 1; $i < 11; $i++)
                            <option value="{{ $i }}"@if($i === 2) selected @endif>{{ $i }}年</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">詳細</label>
                        <input id="description" type="text" class="form-control" name="description" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="ordered-at">注文日</label>
                        <vuejs-datepicker-component default-date="" props-name="ordered_at">
                        </vuejs-datepicker-component>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">作成</button>
                    </div>
                </form>
            </div>
        </div>
    </print-circuit-boards-create>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#print_circuit_boards_create',
    })

</script>
@endsection
