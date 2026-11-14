@extends('layouts.app')

@section('content')
<div id="ordered_lists_create">
    <ordered-lists-create inline-template :props-types='@json($types)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>発注済み依頼の作成</h5>
            </div>
            <div class="card-body">
                <form action="{{route('orderedLists.store')}}" method="post">
                    @csrf
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
                        <label for="model_no" class="text-danger">型番</label>
                        <input id="model_no" type="input" class="form-control" name="model_no" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="lot_size" class="text-danger">入り数</label>
                        <input id="lot_size" type="input" class="form-control" name="lot_size" value="" required>
                    </div>

                    <div class="form-group">
                        <label for="amount" class="text-danger">袋数</label>
                        <div class="input-group mb-3">
                            <input id="lot_amount" type="input" class="form-control" name="lot_amount" value="" required>
                            <select class="custom-select" id="lot_unit_id" name="lot_unit_id">
                                @foreach(LOT_UNIT_ARRAY as $lot_unit_id => $lot_unit)
                                <option value="{{ $lot_unit_id }}">{{ $lot_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <p>仕入れ先</p>
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
                        <div class="form-group col-md-6">
                            <label for="unit_price">単価（円）</label>
                            <input id="unit_price" type="input" class="form-control" name="unit_price" value="">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="depository">棚</label>
                            <input id="depository" type="input" class="form-control" name="depository" value="">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="purchase-at">希望納期</label>
                        <vuejs-datepicker-component default-date="" props-name="purchase_at">
                        </vuejs-datepicker-component>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary w-100">作成</button>
                    </div>
                </form>
            </div>
        </div>
    </ordered-lists-create>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#ordered_lists_create",
    });

</script>
@endsection
