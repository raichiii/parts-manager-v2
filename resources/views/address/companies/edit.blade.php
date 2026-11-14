@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('company.edit',$company))

@section('content')
<div id="companies_edit">
    <companies-edit inline-template :props-url='@json($company->urls)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>会社編集</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('companies.update',[$company]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">会社名 (株式会社等の形態省略)</label>
                        <input :class="errorClassObject('name')" v-model="edit.name" id="name" type="text"
                            class="form-control" name="name" value="{{ $company->name }}" required autofocus>
                        <div class="invalid-feedback">
                            会社名を入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type">会社形態</label>
                        <select id="type" class="custom-select" name="type" v-model="selectedCompanyType">
                            @foreach(CMP_TYPE_ARRAY as $key => $value)
                            <option value="{{$key}}" {{ $company->type == $key ? 'selected' : ''}}>
                                {{$value}}
                            </option>
                            @endforeach
                            <option value="" {{$company->type == null ? 'selected' : ''}}>なし</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="selectedCompanyType != ''">
                        <label for="type_side">会社正式名称表記</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id="type_side_{{CMP_TYPE_SIDE_MAE}}" type="radio"
                                name="type_side" value="{{CMP_TYPE_SIDE_MAE}}" style="width: 30px; height: 15px;"
                                {{$company->type_side == CMP_TYPE_SIDE_MAE ? 'checked' : ''}}>
                            <label class="form-check-label" for="type_side_{{CMP_TYPE_SIDE_MAE}}">
                                前</label>
                            <input class="form-check-input" id="type_side_{{CMP_TYPE_SIDE_ATO}}" type="radio"
                                name="type_side" value="{{CMP_TYPE_SIDE_ATO}}" style="width: 30px; height: 15px;"
                                {{$company->type_side == CMP_TYPE_SIDE_ATO ? 'checked' : ''}}>
                            <label class="form-check-label" for="type_side_{{CMP_TYPE_SIDE_ATO}}">
                                後</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="furigana">会社名(ふりがな)</label>
                        <input :class="errorClassObject('furigana')" v-model="edit.furigana" id="furigana" type="text"
                            class="form-control" name="furigana" value="{{ $company->furigana }}" autofocus>
                        <div class="invalid-feedback">
                            ひらがなで入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="english-name">会社名(英語名)</label>
                        <input :class="errorClassObject('englishName')" v-model="edit.englishName" id="english-name"
                            type="text" class="form-control" name="english_name" value="{{ $company->english_name }}"
                            autofocus>
                        <div class="invalid-feedback">
                            英数字で入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="short_name">呼称</label>
                        <input id="short_name" type="text" class="form-control" name="short_name"
                            value="{{$company->short_name}}" autofocus>
                        <div class="invalid-feedback">
                            呼称を入力してくだい
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="short-english-name">呼称(英語名)</label>
                        <input :class="errorClassObject('shortEnglishName')" v-model="edit.shortEnglishName"
                            id="short-english-name" type="text" class="form-control" name="short_english_name"
                            value="{{ $company->short_english_name }}" autofocus>
                        <div class="invalid-feedback">
                            英数字で入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="url">会社のURL</label>
                        <div v-for="(url, index) in edit.urls" :class="errorClassObject('urls')">
                            <div class="input-group">
                                <input :class="errorClassObject('urls')" v-model="url.value" type="text"
                                    class="form-control" name="urls[]" autofocus>
                                <div class="input-group-append">
                                    <button v-if="index == 0" type="button" class="btn btn-outline-primary"
                                        @click="addUrlInput">追加</button>
                                    <button v-else type="button" class="btn btn-outline-danger"
                                        @click="deleteUrlInput(index)">削除</button>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            http://またはhttps://で始まるURLを入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5">分類</label>
                        <div class="col-md-7">
                            @foreach($classifications as $classification)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" id="classification_{{$classification->id}}"
                                    type="checkbox" name="classifications[]" value="{{$classification->id}}"
                                    style="width: 30px; height: 15px;"
                                    @if(in_array($classification->id,$checkedClassifications)) checked @endif>
                                <label class="form-check-label" for="classification_{{$classification->id}}">
                                    {{$classification->alias_name}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note">備考</label>
                        <textarea id="note" class="form-control" name="note">{{$company->note}}</textarea>
                    </div>
                    <div class="text-center">
                        <button :disabled="isValid == false" type="submit" name="submit"
                            class="btn btn-primary">変更</button>
                    </div>
                </form>

            </div>
        </div>
    </companies-edit>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#companies_edit'
    })

</script>
@endsection
