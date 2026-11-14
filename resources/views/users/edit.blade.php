@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header text-center">
        <h5>ユーザー編集</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update',[$user]) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="username">ユーザー名</label>
                @if(auth()->id() == $user->id)
                <input id="username" type="text" class="form-control" name="username" value="{{ $user->username }}" required
                    autofocus>
                @else
                {{ $user->username }}
                @endif
            </div>
            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                @if(auth()->id() == $user->id)
                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                @else
                {{ $user->name }}
                @endif
            </div>
            <div class="form-group">
                <label for="email">{{ __('E-Mail Address') }}</label>
                @if(auth()->id() == $user->id)
                <input id="email" type="text" class="form-control" name="email" value="{{ $user->email }}" required>
                @else
                {{ $user->email }}
                @endif
            </div>
            @can('isSystemAdmin')
            <div class="form-group">
                <label class="control-label col-md-5">ロール(権限)</label>
                <div class="col-md-7">
                    @foreach($roles as $role)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" id="role_{{$role->id}}" type="checkbox" name="roles[]"
                            value="{{$role->id}}" style="width: 30px; height: 15px;"
                            @if(in_array($role->id,$checkedRoles)) checked
                        @endif>
                        <label class="form-check-label" for="role_{{$role->id}}"> {{$role->alias_name}}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endcan
            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-primary w-100">変更</button>
            </div>
        </form>
    </div>
</div>
@if(auth()->id() == $user->id)
<br>
<div class="card">
    <div class="card-header text-center">
        <h5>パスワード変更</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.updatePassword',[$user]) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label for="new_password">新しいパスワード</label>
                <input id="new_password" type="password" class="form-control" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">新しいパスワード（確認用）</label>
                <input id="confirm_password" type="password" class="form-control" name="confirm_password" required>
            </div>
            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-primary w-100">変更</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
