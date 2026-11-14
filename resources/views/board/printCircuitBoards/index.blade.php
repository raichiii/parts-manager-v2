@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('printCircuitBoards'))

@section('content')
<div id="print_circuit_boards_index">
    <print-circuit-boards-index inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>生基板一覧</h5>
            </div>
            <div class="col-12 text-right">
                <a href="{{route('printCircuitBoards.create')}}">
                    <span class="h6 align-text-bottom text-right">
                        <i class="fas fa-plus-circle"></i>
                        生基板の新規登録
                    </span>
                </a>
            </div>
            <div class="card-body">
                <bootstrap-table-component ref="table" :props-data="{{ $printCircuitBoards }}" :props-columns="columns"
                    @row-click="rowClick" />
            </div>
        </div>
    </print-circuit-boards-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#print_circuit_boards_index",
    });
</script>
@endsection
