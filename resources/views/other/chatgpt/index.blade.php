@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('chatgpt.index'))

@section('content')
<div id="chatgpt-index">
    <chatgpt-index props-chatgpt-api-url="{{ route('chatgpt.chat') }}" inline-template>
        <div class="card">
            <div class="card-header text-center">
                <h5>ChatGPT</h5>
            </div>
            <div class="card-body">
                <div id="chatHistory" ref="chatHistory" class="col-10">
                </div>
            </div>
            <div id="chatgpt-prompt">
                <div class="input-group mb-1">
                    <textarea ref="prompt" v-model="prompt" class="form-control" @input="changePromptHeight" @keydown.shift.enter.prevent="question" :placeholder="placeholderByMode()" autofocus></textarea>
                    <div class="input-group-append col-2" @click="question">
                        <button class="btn btn-outline-success w-100">
                            <i class="fa fa-location-arrow"></i>
                        </button>
                    </div>
                </div>
                <input type="radio" id="mode0" v-model.number="mode" value="0" />
                <label for="mode0">通常モード</label>
                <input type="radio" id="mode1" v-model.number="mode" value="1" />
                <label for="mode1">社長モード</label>
            </div>
            <div id="chatgpt-loading" class="spinner-border" role="status" v-show="isLoading">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </chatgpt-index>
    <div id="chatgpt-bottom-space"></div>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: "#chatgpt-index",
    })
</script>
@endsection
