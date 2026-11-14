@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('unregisteredModels'))

@section('content')
<div id="unregistered_models_index">
    <unregistered-models-index inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>未登録名一覧</h5>
            </div>
            <div class="card-body">
                <div class="row-checks">
                    <bootstrap-table-component ref="table" :props-data="{{$unregisteredModels}}" :props-columns="columns" :props-detail-formatter="detailFormatter" @row-click="rowClick"></bootstrap-table-component>
                </div>
            </div>
        </div>
    </unregistered-models-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#unregistered_models_index",
    });
</script>
@endsection

@section('css')
<style>
    table tr:hover td {
        background-color: #d9efff;
    }

</style>
@endsection
