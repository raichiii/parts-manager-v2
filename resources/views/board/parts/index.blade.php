@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('parts'))

@section('content')
<div id="parts_index">
    <parts-index inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>部品一覧</h5>
            </div>
            <div class="col-12 text-right">
                <a href="{{ route('parts.create') }}">
                    <span class="h6 align-text-bottom text-right">
                        <i class="fas fa-plus-circle"></i>
                        部品の新規作成
                    </span>
                </a>
            </div>
            <div class="card-body">
                <bootstrap-table-component ref="table" :props-data="{{ $parts }}"
                :props-columns="columns" @row-click="rowClick" />
            </div>
        </div>
    </parts-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#parts_index",
    });
</script>
@endsection
