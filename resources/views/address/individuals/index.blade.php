@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('individuals'))

@section('content')

<div id="individuals_index">
    <div class="card">
        <div class="card-header text-center">
            <h5>個人一覧</h5>
        </div>
        <div class="col-12 text-right">
            <a href="{{route('individuals.create')}}">
                <span class="h6 align-text-bottom text-right">
                    <i class="fas fa-plus-circle"></i>
                    個人の新規作成
                </span>
            </a>
        </div>
        <div class="card-body">
            <bootstrap-table-component ref="table" :props-data="{{$individuals}}" :props-columns="columns" @row-click="rowClick">
            </bootstrap-table-component>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    new Vue({
        el: '#individuals_index',
        data() {
            return {
                columns: [{
                        field: "id",
                        title: "ID",
                        sortable: true
                    },
                    {
                        field: "name",
                        title: "顧客名",
                        sortable: true,
                        filterControl: "input",
                        formatter: function (value, row, index) {
                            return '<a href="{{route('individuals.index')}}/'
                            + row.id + '">' + value + '</a>';
                        }
                    },
                ]
            }
        },
        methods: {
            rowClick(...args) {
                const [row, elemet, field] = args;
                window.location.href = "/address/individuals/" + row.id;
            },
        }
    });

</script>
@endsection
