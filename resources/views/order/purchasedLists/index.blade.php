@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('purchasedLists'))

@section('content')
<div id="purchased_lists_index">
    <purchased-lists-index inline-template :props-lot-unit-array='@json(LOT_UNIT_ARRAY)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>購入実績</h5>
            </div>
                <div class="card-body">
                    <form action="?" method="post" id="check-order-upper">
                        @csrf
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-danger col-md-2 offset-md-10 btn-multi" data-toggle="modal" data-target="#confirm-delete" disabled>削除</button>
                        </div>
                        {{ $purchasedLists->links() }}
                        <br>
                        <bootstrap-table-component ref="table" :props-data="{{$purchasedLists->getCollection()}}" :props-columns="columns" @row-click="rowClick"></bootstrap-table-component>
                        <br>
                        {{ $purchasedLists->links() }}
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-danger col-md-2 offset-md-10 btn-multi" data-toggle="modal" data-target="#confirm-delete" disabled>削除</button>
                        </div>
                        <comfirm-component props-order-url="" props-receive-url="" props-delete-url="{{ route('purchasedLists.multiDelete') }}"></comfirm-component>
                    </form>
                </form>
            </div>
        </div>
    </purchased-lists-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#purchased_lists_index",
    })
    $(function () {
        //行選択チェックボックス
        $('.table-bordered').change(function () {
            // チェックされていればボタンを有効化
            // 行が全てチェックされていれば全選択チェックボックスをtrueに（逆も然り）
            console.log($("tbody :checked").length + ' checked')
            if (!$("tbody :checked").length) {
                $(".btn-multi").prop("disabled", true)
                $('.check-all').prop("checked", false)
                return false
            } else if ($("tbody :checked").length === $("tbody :input").length) {
                $(".btn-multi").prop("disabled", false)
                $('.check-all').prop("checked", true)
            } else {
                $(".btn-multi").prop("disabled", false)
            }
        })
    })
</script>
<script src="{{ asset('js/CheckAll.js') }}"></script>
@endsection
