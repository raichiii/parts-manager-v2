@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('jobs'))

@section('content')
<div id="jobs_index">
    <jobs-index inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>Job一覧</h5>
            </div>
            <div class="col-12 text-right">
                <a href="{{route('jobs.create')}}">
                    <span class="h4 align-text-bottom text-right">
                        <i class="fas fa-plus-circle"></i>
                        Jobの新規作成
                    </span>
                </a>
            </div>
            <div class="card-body">
                <bootstrap-table-component ref="table" :props-data="{{$jobs}}" :props-columns="columns"
                    @row-click="rowClick" />
            </div>
        </div>
    </jobs-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#jobs_index",
    });

</script>
@endsection
