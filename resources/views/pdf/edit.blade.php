@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('purchaseOrder.edit'))

@section('content')
<div id="requested_lists_edit">
    <div class="card">
        <div class="card-header text-center">
            <h5>注文書の編集</h5>
        </div>
        <div class="card-body">
            <h5 class="text-center">【{{ $requestedLists[0]->supplierName }}】</h5>
            <form action="?" method="post" target="_blank">
                @csrf

                <div class="btn-toolbar w-100">
                    <button class="btn btn-warning w-100" formaction="{{route('purchaseOrder.make')}}" style="margin-bottom: 10px;">
                        注文書作成
                    </button>
                </div>

                <input type="hidden" name="supplier_name" value="{{ $requestedLists[0]->supplierName }}">

                <div class="form-row">
                    <div class="col-md-4">
                        <label for="customer">顧客</label>
                        @if($requestedLists[0]->supplier && $requestedLists[0]->supplier->customers->count())
                        <select id="customer" name="customer" class="custom-select col-md-8">
                            @foreach($requestedLists[0]->supplier->customers as $customer)
                            <option value="{{ $customer->name }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="input" id="customer" name="customer" class="form-control col-md-8" placeholder="顧客登録がありません" style="display: inline-block;">
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label for="phone">電話番号</label>
                        @if($requestedLists[0]->supplier && $requestedLists[0]->supplier->offices->count())
                        <select id="phone" name="phone" class="custom-select col-md-8">
                            @foreach($requestedLists[0]->supplier->offices as $office)
                            @foreach($office->phones as $phone)
                            <option value="{{ $phone }}">{{ $phone }}</option>
                            @endforeach
                            @endforeach
                        </select>
                        @else
                        <input type="input" id="phone" name="phone" class="form-control col-md-8" placeholder="電話番号登録がありません" style="display: inline-block;">
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label for="fax">FAX</label>
                        @if($requestedLists[0]->supplier && $requestedLists[0]->supplier->offices->count())
                        <select id="fax" name="fax" class="custom-select col-md-8">
                            @foreach($requestedLists[0]->supplier->offices as $office)
                            @foreach($office->faxes as $fax)
                            <option value="{{ $fax }}">{{ $fax }}</option>
                            @endforeach
                            @endforeach
                        </select>
                        @else
                        <input type="input" id="fax" name="fax" class="form-control col-md-8" placeholder="FAX登録がありません" style="display: inline-block;">
                        @endif
                    </div>
                </div>

                @foreach($requestedLists as $requestedList)
                <div class="text-center">
                    <h3>--- {{$loop->index + 1}} ---</h3>
                </div>
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="order_no">注文番号</label>
                        <input type="input" id="order_no" name="order_no[]"
                            value="{{ $requestedList->job->number }}-{{ $requestedList->id }}">
                    </div>
                    <div class="col-md-3">
                        <label for="name">品名　　</label>
                        <input type="input" id="name" name="name[]"
                            value="{{ $requestedList->part? $requestedList->part->name : '基板' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="model_no">型番　　　</label>
                        <input type="input" id="model_no" name="model_no[]"
                            value="{{ $requestedList->modelNoName}}">
                    </div>
                    <div class="col-md-3">
                        <label for="maker">メーカー</label>
                        <input type="input" id="maker" name="maker[]"
                            value="{{$requestedList->thing? $requestedList->thing->maker->name : null}}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="number">数量　　</label>
                        <input type="input" id="number" name="number[]"
                            value="@if($requestedList->lot_size >1){{$requestedList->lot_size}}個入り@endif{{$requestedList->lot_amount}}{{ LOT_UNIT_ARRAY[$requestedList->lot_unit_id] }}">
                    </div>
                        <div class="col-md-3">
                        <label for="purchase-at">希望納期</label>
                        <simple-datepicker-component default-date="{{$requestedList->purchase_at}}" props-name="purchase_at[]" style="display: inline-block;">
                        </simple-datepicker-component>
                    </div>
                    <div class="col-md-3">
                        <label for="code">商品コード</label>
                        <input type="input" id="code" name="code[]">
                    </div>
                    <div class="col-md-3">
                        <label for="note">備考　　</label>
                        <input type="input" id="note" name="note[]">
                    </div>
                </div>
                @endforeach
                <div class="btn-toolbar w-100">
                    <button class="btn btn-warning w-100" formaction="{{route('purchaseOrder.make')}}">注文書作成</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#requested_lists_edit",
    });

</script>
@endsection
