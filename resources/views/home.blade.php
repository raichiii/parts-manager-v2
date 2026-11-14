@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Dashboard') }}</div>

    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <br>
        <div class="form-row">
            <div class="text-center col-md-6">
                @if (count($suppliers))
                <div class="btn btn-danger blink-text">今なら発注間に合います</div>
                @endif
                @foreach ($suppliers as $supplier)
                <p style="padding: 0px; margin: 0px;">・{{ $supplier }}</p>
                @endforeach
            </div>
            <div class="text-center col-md-6">
                @if (count($deadlineBoards))
                <div class="btn btn-warning blink-text">基板の保管期限が近づいています</div>
                @endif
                @foreach ($deadlineBoards as $deadlineBoard)
                <p  style="padding: 0px; margin: 0px;">・{{ $deadlineBoard->thing->model_no }}[{{ $deadlineBoard->thing->maker->name }}] ~{{ $deadlineBoard->deadline }}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div id="home-illust" class="text-right">
    <img src="/storage/images/undraw_team_collaboration_re_ow29.svg" alt="home-illust" class="mt-3">
</div>

@endsection
