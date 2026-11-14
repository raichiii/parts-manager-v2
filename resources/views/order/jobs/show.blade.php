@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('job', $job))

@section('content')
<div class="card">
    @component('components.delete_modal',['id' => $job->id, 'title' => 'ジョブの削除', 'text' => $job->number,
    'action' => route('jobs.destroy',['job' => $job])])
    @endcomponent
    <div class="card-header text-center">
        <h5>Job情報</h5>
    </div>
    <div class="col-12 text-right">
        <a href="{{route('requestedLists.create')}}?job_no={{$job->number}}">
            <span class="h6 align-text-bottom text-right">
                <i class="fas fa-plus-circle"></i>
                購入依頼の作成
            </span>
        </a>
    </div>
    <div class="card-body">
        <ul class="h3">
            <li>JobNo: {{ $job->number}}</li>
            <li>仕事名: {{ $job->name}}</li>
            <li>依頼元(会社)：
                @if($job->requester)
                <a href="{{route('companies.show',[$job->requester])}}">
                    {{ $job->requester->name}}
                </a>
                @endif
            </li>
            <li>納品先(会社)：
                @if($job->deliveryDestination)
                <a href="{{route('companies.show',[$job->deliveryDestination])}}">
                    {{ $job->deliveryDestination->name}}
                </a>
                @endif
            </li>
            <li>詳細：{{ $job->description }}</li>
        </ul>
        <div class="btn-toolbar w-100">
            <a class="btn btn-outline-primary col-md-9" href="{{route('jobs.edit',[$job])}}" role="button">
                編集
            </a>
            <button class="btn btn-outline-danger col-md-3" data-toggle="modal" data-target="#deleteConfirmModal_{{$job->id}}">
                削除
            </button>
        </div>
    </div>
</div>
@endsection
