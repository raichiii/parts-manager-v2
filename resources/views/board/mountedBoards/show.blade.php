@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('mountedBoard', $routePrintCircuitBoard, $mountedBoard))

@section('content')
<div class="card">
    @component('components.delete_modal',['id' => $mountedBoard->id, 'title' => '基板の削除', 'text' =>
    ($mountedBoard->version_name == null ? 'なし' : $mountedBoard->version_name) .
    "(バージョン" . $mountedBoard->version . ")",
    'action' => route('mountedBoards.destroy',[$routePrintCircuitBoard,$mountedBoard])])
    @endcomponent
    <div class="card-header text-center">
        <h5>基板情報</h5>
    </div>
    <div class="col-12 text-right">
        <a href="{{route('mountedBoards.create',[$routePrintCircuitBoard])}}">
            <span class="h6 align-text-bottom text-right">
                <i class="fas fa-plus-circle"></i>
                基板の新規登録
            </span>
        </a>
    </div>
    <div class="card-body">
        <ul>
            <li>基板名：{{$routePrintCircuitBoard->thing->model_no}}</li>
            <li>バージョン名： {{$mountedBoard->version_name == null ? 'なし' : $mountedBoard->version_name}}</li>
            <li>バージョン：{{$mountedBoard->version}}</li>
            <li>詳細：{{$mountedBoard->description}}</li>
            <li>部品:
                <ul>
                    @foreach($parts as $part)
                    <li>
                        <a href="{{route('parts.show',[$part])}}">
                            {{$part->pivot->reference_number}}. {{ $part->name }}[{{$part->thing->model_no}}]
                        </a>
                        :
                        @if($part->pivot->is_mounted)
                        <span class="text text-success">実装済</span>
                        @else
                        <span class="text text-danger">未実装</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </li>
            <li>作成日：{{$mountedBoard->created_at}}</li>
            <li>更新日：{{$mountedBoard->updated_at}}</li>
        </ul>
        <div class="text-center">
            <a class="btn btn-outline-primary"
                href="{{route('mountedBoards.edit',[$routePrintCircuitBoard, $mountedBoard])}}" role="button">
                編集
            </a>
            <button class="btn btn-outline-danger" data-toggle="modal"
                data-target="#deleteConfirmModal_{{$mountedBoard->id}}">
                削除
            </button>
        </div>
    </div>

</div>
@endsection
