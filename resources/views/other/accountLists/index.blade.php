@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('accountLists'))

@section('content')
<div class="card">
    <div class="card-header text-center">
        <h5>仕入れ先用アカウント一覧</h5>
    </div>
    <div class="col-12 text-right">
        <a href="{{route('accountLists.create')}}">
            <span class="h6 align-text-bottom text-right">
                <i class="fas fa-plus-circle"></i>
                アカウントの新規登録
            </span>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead class="border-bottom border-danger">
                    <tr>
                        <th style="width: 20%">仕入れ先</th>
                        <th style="width: 20%">アカウント名</th>
                        <th style="width: 20%">パスワード</th>
                        <th style="width: 30">備考</th>
                        <th style="width: 20%;text-align: center">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accountLists as $accountList)
                    <tr class="">
                        <td>
                            <a href="{{route('companies.show',[$accountList->company])}}">
                                {{ $accountList->company->short_name}}
                            </a>
                        </td>
                        <td>
                            <div class="form-inline">
                                <input style="width:80%;" type="text" readonly class="form-control"
                                    value="{{ $accountList->name}}" id="account-{{$accountList->id}}">
                                <button type="button" class="btn btn-outline-info" outline data-toggle="popover"
                                    title="コピーしました" onclick="copyToClipboard('account-{{$accountList->id}}')">
                                    <i class="fas fa-clipboard"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="form-inline">
                                <input style="width:80%;" type="text" readonly class="form-control"
                                    value="{{ $accountList->password}}" id="password-{{$accountList->id}}">
                                <button type="button" class="btn btn-outline-info" outline data-toggle="popover"
                                    title="コピーしました" onclick="copyToClipboard('password-{{$accountList->id}}')">
                                    <i class="fas fa-clipboard"></i>
                                </button>
                            </div>
                        </td>
                        <td>{{$accountList->note}}</td>
                        <td nowrap style="text-align: center">
                            <a class="btn btn-outline-primary" href="{{route('accountLists.show', [$accountList])}}">
                                詳細
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function () {
        $('[data-toggle="popover"]').popover().click(function () {
            let pop = $(this);
            setTimeout(function () {
                pop.popover('hide');
            }, 2000)
        });
    });

    function copyToClipboard(id) {
        var text = document.getElementById(id).value;
        var area = document.createElement("textarea")
        area.textContent = text
        document.body.appendChild(area)
        area.select()
        document.execCommand("copy")
        document.body.removeChild(area)
    }

</script>
@endsection
