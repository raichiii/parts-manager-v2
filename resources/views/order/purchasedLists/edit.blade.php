@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('purchasedList.edit', $purchasedList))

@section('content')
<div id="purchased_lists_edit">
    <purchased-lists-edit inline-template :props-purchased-list='@json($purchasedList)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>購入実績の編集</h5>
            </div>
            <div class="card-body">
                <form action="?" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="job_no" class="text-danger">JobNo</label>
                        <auto-complete-component props-url="{{route('jobs.search')}}" props-name="job_no"
                            v-model="suggestionInput.job" @suggest="getResults('job',$event)" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.job.length && open.job">
                            <ul class="list-group">
                                <li v-for="(result, index) in results.job" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action"
                                    @click="suggestionClick('job',index)"
                                    v-text="suggestionList('job',index)">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="model_no">型番</label>
                        <auto-complete-component props-url="{{route('things.search')}}" props-name="model_no"
                            v-model="suggestionInput.model_no" @suggest="getResults('model_no',$event)" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.model_no.length && open.model_no">
                            <ul class="list-group">
                                <li v-for="(result, index) in results.model_no" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action"
                                    @click="suggestionClick('model_no',index)"
                                    v-text="suggestionList('model_no',index)">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lot_size" class="text-danger">入り数</label>
                        <input id="lot_size" type="input" class="form-control" name="lot_size" value={{$purchasedList->lot_size}} required>
                    </div>

                    <div class="form-group">
                        <label for="amount" class="text-danger">袋数</label>
                        <div class="input-group mb-3">
                            <input id="lot_amount" type="input" class="form-control" name="lot_amount" value={{$purchasedList->lot_amount}} required>
                            <select class="custom-select" id="lot_unit_id" name="lot_unit_id">
                                @foreach(LOT_UNIT_ARRAY as $lot_unit_id => $lot_unit)
                                @if($lot_unit_id === $purchasedList->lot_unit_id)
                                <option selected value="{{ $lot_unit_id }}">{{ $lot_unit }}</option>
                                @else
                                <option value="{{ $lot_unit_id }}">{{ $lot_unit }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <p class="text-danger">仕入れ先</p>
                        <auto-complete-component props-url="{{route('companies.search')}}" props-name="supplier"
                            v-model="suggestionInput.supplier" @suggest="getResults('supplier',$event)" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.supplier.length && open.supplier">
                            <ul class="list-group">
                                <li v-for="(result, index) in results.supplier" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action"
                                    @click="suggestionClick('supplier',index)"
                                    v-text="suggestionList('supplier',index)">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="unit_price" class="text-danger">袋単価（円）</label>
                            <input id="unit_price" type="input" class="form-control" name="unit_price" value={{$purchasedList->unit_price}} required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="depository" class="text-danger">棚</label>
                            <input id="depository" type="input" class="form-control" name="depository" value={{$purchasedList->depository}} required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="purchase-at">希望納期</label>
                        <vuejs-datepicker-component default-date="{{$purchasedList->purchase_at}}" props-name="purchase_at">
                        </vuejs-datepicker-component>
                    </div>
                    <div class="btn-toolbar w-100">
                        <button class="btn btn-primary col-md-9" formaction="{{route('purchasedLists.update',[$purchasedList])}}">変更</button>
                        <button class="btn btn-success col-md-3" formaction="{{route('requestedLists.replicate',[$purchasedList])}}">依頼の複製</button>
                    </div>
                </form>
            </div>
        </div>
    </purchased-lists-edit>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#purchased_lists_edit",
    });

</script>
@endsection
