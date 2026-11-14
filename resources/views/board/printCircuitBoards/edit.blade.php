@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('printCircuitBoard.edit', $printCircuitBoard))

@section('content')
<!-- <div class="container"> -->
<div id="print_circuit_boards_edit">
    <print-circuit-boards-edit inline-template :props-maker='@json($printCircuitBoard->maker)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>生基板編集</h5>
            </div>
            <div class="card-body">
                <form action="{{route('printCircuitBoards.update', [$printCircuitBoard]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="select-maker">メーカー</label>
                        <auto-complete-component props-url="{{route('companies.search')}}" :props-query="query"
                            props-name="maker" v-model="suggestionInput" @suggest="getResults" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.length && open">
                            <ul class="list-group">
                                <li v-for="(result, index) in results" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action" @click="suggestionClick(index)"
                                    v-text="suggestionList(index)">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="model_no">基板名（型番）</label>
                        <input id="model_no" type="text" class="form-control" name="model_no"
                            value="{{ $printCircuitBoard->thing->model_no }}" required>
                    </div>
                    <div class="form-group">
                        <label for="valid-years">保管期間</label>
                        <select id="valid-years" class="custom-select" name="valid_years">
                            <option value="-1" @if($printCircuitBoard->valid_years === -1)
                                selected @endif>永久</option>
                            <option value="0" @if(!$printCircuitBoard->valid_years)
                                selected @endif>廃業</option>
                            @for($i=1;$i<11;$i++)
                            <option value="{{ $i }}" @if($printCircuitBoard->valid_years === $i)
                                selected @endif>{{ $i }}年</option>
                            @endfor
                        </select>


                    </div>
                    <div class="form-group">
                        <label for="description">詳細</label>
                        <input id="description" type="text" class="form-control" name="description"
                            value="{{$printCircuitBoard->description}}" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="ordered-at">注文日</label>
                        <vuejs-datepicker-component default-date="{{$printCircuitBoard->ordered_at}}" props-name="ordered_at">
                        </vuejs-datepicker-component>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary">変更</button>
                    </div>
                </form>
            </div>
        </div>
    </print-circuit-boards-edit>
</div>
<!-- </div> -->
@endsection

@section('js')
<script>
    new Vue({
        el: '#print_circuit_boards_edit',
    })

</script>
@endsection
