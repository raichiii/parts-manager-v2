@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('job.edit', $job))

@section('content')
<div id="jobs_edit">
    <jobs-edit inline-template :props-job='@json($job)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>Jobの編集</h5>
            </div>
            <div class="card-body">
                <form action="{{route('jobs.update',[$job])}}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="number">JobNo</label>
                        <input id="number" type="input" class="form-control" name="number" value="{{$job->number}}"
                            required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="name">仕事名</label>
                        <input id="name" type="input" class="form-control" name="name" value="{{$job->name}}" required
                            autofocus>
                    </div>
                    <div class=" form-group">
                        <label for="requester">依頼元(会社)</label>
                        <auto-complete-component props-url="{{route('companies.search')}}" :props-query="query"
                            props-name="requester" v-model="suggestionInput.requester"
                            @suggest="getResults('requester',$event)" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.requester.length && open.requester">
                            <ul class="list-group">
                                <li v-for="(result, index) in results.requester" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action"
                                    @click="suggestionClick('requester',index)"
                                    v-text="suggestionList('requester',index)">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="delivery-destination">納入先(会社)</label>
                        <auto-complete-component props-url="{{route('companies.search')}}"
                            props-name="delivery_destination" v-model="suggestionInput.deliveryDestination"
                            @suggest="getResults('deliveryDestination',$event)" />
                        </auto-complete-component>
                        <div class="panel-footer" v-if="results.deliveryDestination.length && open.deliveryDestination">
                            <ul class="list-group">
                                <li v-for="(result, index) in results.deliveryDestination" v-bind:key="result.index"
                                    class="list-group-item list-group-item-action"
                                    @click="suggestionClick('deliveryDestination',index)"
                                    v-text="suggestionList('deliveryDestination',index)">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class=" form-group">
                        <label for="description">詳細</label>
                        <input id="description" type="text" class="form-control" name="description"
                            value="{{$job->description}}" autofocus>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary w-100">変更</button>
                    </div>
                </form>
            </div>
        </div>
    </jobs-edit>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#jobs_edit',
    })

</script>
@endsection
