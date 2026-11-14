@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('orderedLists'))

@section('content')
<div id="ordered_lists_index">
    <ordered-lists-index inline-template :props-lot-unit-array='@json(LOT_UNIT_ARRAY)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>発注済みリスト</h5>
            </div>
                <div class="card-body">
                    <div class="col-12 clearfix">
                        <div class="float-right">
                            <div class="row">
                                <file-upload-component props-file-name="file-ordered-list" props-dialog-name="発注済みリストインポート"
                                props-url="{{ route('orderedLists.import') }}" :props-csrf="{{ json_encode(csrf_token()) }}"></file-upload-component>
                            </div>
                        </div>
                    </div>
                    <br>
                    <form action="?" method="post" id="check-order-upper">
                        @csrf
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-success col-md-10 mr-1 btn-multi btn-receive" data-toggle="modal" data-target="#confirm-receive" disabled>受領</button>
                            <button type="button" class="btn btn-danger col-md-2 btn-multi" data-toggle="modal" data-target="#confirm-delete" disabled>削除</button>
                        </div>
                        <br>
                        <bootstrap-table-component ref="table" :props-data="{{$orderedLists}}" :props-columns="columns" @row-click="rowClick"></bootstrap-table-component>
                        <br>
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-success col-md-10 mr-1 btn-multi btn-receive" data-toggle="modal" data-target="#confirm-receive" disabled>受領</button>
                            <button type="button" class="btn btn-danger col-md-2 btn-multi" data-toggle="modal" data-target="#confirm-delete" disabled>削除</button>
                        </div>
                        <comfirm-component props-order-url="" props-receive-url="{{ route('orderedLists.multiReceive') }}" props-delete-url="{{ route('orderedLists.multiDelete') }}"></comfirm-component>
                    </form>
                </form>
            </div>
        </div>
    </ordered-lists-index>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#ordered_lists_index",
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
            // 仕入れ先がなければ発注を無効化->終了
            const $firstSupplier = $($("tbody :checked")[0]).parent().parent().nextAll().find(".table-supplier").text()
            let $supplier
            $("tbody :checked").each(function(i) {
                $supplier = $(this).parent().parent().nextAll().find(".table-supplier").text()
                if($supplier === "-") {
                    $('.btn-receive').attr('disabled', true)
                    return false
                }
            })
        })
    })
</script>
<script src="{{ asset('js/CheckAll.js') }}"></script>
@endsection
