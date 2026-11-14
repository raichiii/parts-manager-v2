@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('printCircuitBoard', $printCircuitBoard))

@section('content')
<div class="card">
    @component('components.delete_modal',['id' => $printCircuitBoard->id, 'title' => '生基板の削除', 'text' =>
    $printCircuitBoard->name, 'action'
    => route('printCircuitBoards.destroy',[$printCircuitBoard])])
    @endcomponent
    <div class="card-header text-center">
        <h5>生基板情報</h5>
    </div>
    <div class="col-12 text-right">
        <a href="{{route('mountedBoards.create',[$printCircuitBoard])}}">
            <span class="h4 align-text-bottom text-right">
                <i class="fas fa-plus-circle"></i>
                基板の新規作成
            </span>
        </a>
    </div>
    <div class="card-body">
        <ul class="h3">
            <li>生基板名: {{$printCircuitBoard->thing->model_no}}</li>
            <li>メーカー:
                <a href="{{route('companies.show',[$printCircuitBoard->maker])}}">
                    {{$printCircuitBoard->maker->name}}
                </a>
            </li>
            @if($printCircuitBoard->valid_years === -1)
            <li>保管期間: 永久</li>
            @elseif(!$printCircuitBoard->valid_years)
            <li>保管期間: 廃業</li>
            @else
            <li>保管期間: {{$printCircuitBoard->valid_years}}年</li>
            @endif
            <li>詳細: {{$printCircuitBoard->description}}</li>
        </ul>
        <div class="btn-toolbar w-100">
            <a class="btn btn-outline-primary col-md-9" href="{{route('printCircuitBoards.edit',[$printCircuitBoard])}}"
                role="button">
                編集
            </a>
            <button class="btn btn-outline-danger col-md-3" data-toggle="modal"
                data-target="#deleteConfirmModal_{{$printCircuitBoard->id}}">
                削除
            </button>
        </div>
        @if(!$mountedBoards->isEmpty())
        <h3 class="text-center mt-5">基板一覧</h3>
        <div class="table-responsive">
            <table class="table">
                <thead class="border-bottom border-danger">
                    <tr>
                        <th style="width: 25%">バージョン名</th>
                        <th style="width: 15%">バージョン</th>
                        <th style="width: 40%">詳細</th>
                        <th style="width: 20%">更新日</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mountedBoards as $mountedBoard)
                    <tr class="" data-href="{{route('mountedBoards.show',[$routePrintCircuitBoard,$mountedBoard])}}">
                        <td>{{$mountedBoard->version_name == null ? 'なし' : $mountedBoard->version_name}}</td>
                        <td>{{$mountedBoard->version}}</td>
                        <td>{{$mountedBoard->description}}</td>
                        <td>{{$mountedBoard->updated_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    $(function () {
        $('tr[data-href]', 'table.table').click(function () {
            location.href = $(this).data('href');
        });
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
