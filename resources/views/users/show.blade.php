@extends('layouts.app')

@section('content')
<!-- <div class="container"> -->
<div class="card">
    <div class="card-header text-center">ユーザー情報</div>
    <div class="card-body">
        <ul>
            <li>　ユーザー名　：{{ $user->username }}</li>
            <li>　　表示名　　：{{ $user->name }}</li>
            <li>メールアドレス：{{ $user->email }}</li>
            <li>　　ロール　　：
                @foreach($user->roles as $role)
                    @if($user->hasRole($role))
                        {{$role->alias_name}}
                    @endif
                @endforeach
            </li>
        </ul>
        @if(auth()->id() == $user->id || Gate::allows('isSystemAdmin'))
        <div class="text-center">
            <a class="btn btn-outline-primary w-100" href="{{route('users.edit',[$user])}}" role="button">
                編集
            </a>
        </div>
        @endif
    </div>
</div>
<!-- </div> -->
@endsection
