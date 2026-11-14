@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('others'))

@section('content')
<div id="others_index">
    <others-index inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>その他一覧</h5>
            </div>
            <div class="col-12 text-right">
                <a href="{{ route('others.create') }}">
                    <span class="h6 align-text-bottom text-right">
                        <i class="fas fa-plus-circle"></i>
                        その他の新規作成
                    </span>
                </a>
            </div>
            <div class="card-body">
                <bootstrap-table-component ref="table" :props-data="{{ $others }}" :props-columns="columns"
                    @row-click="rowClick" />
            </div>
        </div>
    </others-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#others_index",
    });
</script>
@endsection
