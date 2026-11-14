@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('requestedLists'))

@section('content')
<div id="requested_lists_index">
    <requested-lists-index inline-template :props-lot-unit-array='@json(LOT_UNIT_ARRAY)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>注文依頼リスト</h5>
            </div>
            <div class="card-body">
                <div class="col-12 clearfix">
                    <div class="float-right">
                        <div class="row">
                            <file-upload-component props-file-name="file-requested-list" props-dialog-name="購入依頼インポート"
                            props-url="{{ route('requestedLists.import') }}" :props-csrf="{{ json_encode(csrf_token()) }}"></file-upload-component>
                        </div>
                    </div>
                </div>
                <br>
                <form action="?" method="post">
                    @csrf
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-success col-md-5 mr-1 btn-multi btn-order" data-toggle="modal" data-target="#confirm-order" disabled>発注</button>
                        <button class="btn btn-warning col-md-5 mr-1 btn-multi btn-purchase-order" formaction="{{ route('purchaseOrder.edit') }}" disabled>注文書作成</button>
                        <button type="button" class="btn btn-danger col-md-2 btn-multi" data-toggle="modal" data-target="#confirm-delete" disabled>削除</button>
                    </div>
                    <br>
                    <div class="row-checks">
                        <bootstrap-table-component ref="table" :props-data="{{$requestedLists}}" :props-columns="columns" @row-click="rowClick" />
                    </div>
                    <br>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-success col-md-5 mr-1 btn-multi btn-order" data-toggle="modal" data-target="#confirm-order" disabled>発注</button>
                        <button class="btn btn-warning col-md-5 mr-1 btn-multi btn-purchase-order" formaction="{{ route('purchaseOrder.edit') }}" disabled>注文書作成</button>
                        <button type="button" class="btn btn-danger col-md-2 btn-multi" data-toggle="modal" data-target="#confirm-delete" disabled>削除</button>
                    </div>
                    <comfirm-component props-order-url="{{ route('requestedLists.multiOrder') }}" props-receive-url="" props-delete-url="{{ route('requestedLists.multiDelete') }}"></comfirm-component>
                </form>
            </div>
        </div>
    </requested-lists-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#requested_lists_index",
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
            // 仕入れ先が一致しなければ注文書作成を無効化
            // 仕入れ先がなければ発注/注文書作成を無効化->終了
            const $firstSupplier = $($("tbody :checked")[0]).parent().parent().nextAll().find(".table-supplier").text()
            let $supplier
            $("tbody :checked").each(function(i) {
                $supplier = $(this).parent().parent().nextAll().find(".table-supplier").text()
                if($firstSupplier != $supplier) {
                    $('.btn-purchase-order').attr('disabled', true)
                }
                if($supplier === "-") {
                    $('.btn-order').attr('disabled', true)
                    $('.btn-purchase-order').attr('disabled', true)
                    return false
                }
            })
        })
    })
</script>
<script src="{{ asset('js/CheckAll.js') }}"></script>
@endsection
