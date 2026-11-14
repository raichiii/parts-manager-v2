@extends('layouts.app')

@section('content')
<!-- <div class="container"> -->

<div class="card" id="users-index">
    <div class="card-header text-center">
        <h5>未登録一覧</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead class="border-bottom border-danger">
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 30%">名前</th>
                        <th style="width: 30%">タイプ</th>
                        <th style="width: 30%">作成日</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unregisteredLists as $unregisteredList)
                    <tr class="">
                        <td>{{$unregisteredList->id}}</td>
                        <td>{{$unregisteredList->name}}</td>
                        <td>
                            @if($unregisteredList->type == 'Part')
                            部品
                            @elseif($unregisteredList->type == 'PrintCircuitBoard')
                            生基板
                            @elseif($unregisteredList->type == 'Maker')
                            メーカー
                            @elseif($unregisteredList->type == 'Supplier')
                            依頼元
                            @elseif($unregisteredList->type == 'Exception')
                            その他
                            @endif
                        </td>
                        <td nowrap>
                            {{$unregisteredList->created_at}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
