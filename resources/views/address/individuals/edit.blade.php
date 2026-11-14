@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('individual.edit',$individual))

@section('content')
<!-- <div class="container"> -->
<div id="individuals_edit">
    <individuals-edit inline-template :props-individual='@json($individual)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>個人編集</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('individuals.update',['individual' => $individual]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">名前</label>
                        <input :class="errorClassObject('name')" v-model="edit.name" id="name" type="text"
                            class="form-control" name="name" required autofocus>
                        <div class="invalid-feedback">
                            名前を入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="furigana">名前(ふりがな)</label>
                        <input :class="errorClassObject('furigana')" v-model="edit.furigana" id="furigana" type="text"
                            class="form-control" name="furigana" autofocus>
                        <div class="invalid-feedback">
                            ひらがなで入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="english-name">名前(英語表記)</label>
                        <input :class="errorClassObject('englishName')" v-model="edit.englishName" id="english-name"
                            type="text" class="form-control" name="english_name" autofocus>
                        <div class="invalid-feedback">
                            英数字で入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="postalcode">郵便番号</label>
                        <input :class="errorClassObject('postalcode')" v-model="edit.postalcode" id="postalcode"
                            type="text" class="form-control" name="postalcode" required autofocus>
                        <div class="invalid-feedback">
                            ○○○-○○○○形式で入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select-prefecture">都道府県</label>
                        <select id="select-prefecture" class="custom-select" name="prefecture_id">
                            @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture->id}}" @if($individual->prefecture->id == $prefecture->id)
                                selected @endif>{{ $prefecture->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="address">住所</label>
                        <input :class="errorClassObject('address')" v-model="edit.address" id="address" type="text"
                            class="form-control" name="address" required autofocus>
                        <div class="invalid-feedback">
                            正しく入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phones">電話番号</label>
                        <div v-for="(phone, index) in edit.phones" :class="errorClassObject('phones')">
                            <div class="input-group">
                                <input :class="errorClassObject('phones')" v-model="phone.value" type="text"
                                    class="form-control" name="phones[]" autofocus>
                                <div class="input-group-append">
                                    <button v-if="index == 0" type="button" class="btn btn-outline-primary"
                                        @click="addInput('phones')">追加</button>
                                    <button v-else type="button" class="btn btn-outline-danger"
                                        @click="deleteInput('phones', index)">削除</button>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            数字とハイフンしか含めません
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="faxes">Fax</label>
                        <div v-for="(fax, index) in edit.faxes" :class="errorClassObject('faxes')">
                            <div class="input-group">
                                <input :class="errorClassObject('faxes')" v-model="fax.value" type="text"
                                    class="form-control" name="faxes[]" autofocus>
                                <div class="input-group-append">
                                    <button v-if="index == 0" type="button" class="btn btn-outline-primary"
                                        @click="addInput('faxes')">追加</button>
                                    <button v-else type="button" class="btn btn-outline-danger"
                                        @click="deleteInput('faxes', index)">削除</button>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            数字とハイフンしか含めません
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="emails">メールアドレス</label>
                        <div v-for="(email, index) in edit.emails" :class="errorClassObject('emails')">
                            <div class="input-group">
                                <input :class="errorClassObject('emails')" v-model="email.value" type="text"
                                    class="form-control" name="emails[]" autofocus>
                                <div class="input-group-append">
                                    <button v-if="index == 0" type="button" class="btn btn-outline-primary"
                                        @click="addInput('emails')">追加</button>
                                    <button v-else type="button" class="btn btn-outline-danger"
                                        @click="deleteInput('emails', index)">削除</button>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            正しい形式のメールアドレスを入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <div v-for="(url, index) in edit.urls" :class="errorClassObject('urls')">
                            <div class="input-group">
                                <input :class="errorClassObject('urls')" v-model="url.value" type="text"
                                    class="form-control" name="urls[]" autofocus>
                                <div class="input-group-append">
                                    <button v-if="index == 0" type="button" class="btn btn-outline-primary"
                                        @click="addInput('urls')">追加</button>
                                    <button v-else type="button" class="btn btn-outline-danger"
                                        @click="deleteInput('urls', index)">削除</button>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            http://またはhttps://で始まるURLを入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note">備考</label>
                        <textarea id="note" class="form-control" name="note">{{$individual->note}}</textarea>
                    </div>
                    <div class="text-center">
                        <button :disabled="isValid == false" type="submit" name="submit"
                            class="btn btn-primary">変更</button>
                    </div>
                </form>
            </div>
        </div>
    </individuals-edit>
</div>
<!-- </div> -->
@endsection

@section('js')
<script>
    new Vue({
        el: '#individuals_edit'
    })

</script>
@endsection
