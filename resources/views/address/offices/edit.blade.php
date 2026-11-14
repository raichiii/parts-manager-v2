@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('office.edit',$routeCompany,$office))

@section('content')
<!-- <div class="container"> -->
<div id="offices_edit">
    <offices-edit inline-template :props-office='@json($office)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>事務所編集</h5>
            </div>
            <div class="card-body">
                <form action="{{route('offices.update', [$routeCompany, $office]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="select-company">会社</label>
                        <select id="select-company" class="custom-select" name="company_id">
                            <option value="{{$routeCompany->id}}" selected>{{$office->company->full_name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">事務所名</label>
                        <input :class="errorClassObject('name')" v-model="edit.name" id="name" type="text"
                            class="form-control" name="name" required autofocus>
                        <div class="invalid-feedback">
                            事務所名を入力してください
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
                            <option value="{{ $prefecture->id}}" @if($office->prefecture->id == $prefecture->id)
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
                        <label for="building">ビル名</label>
                        <input id="building" type="text" class="form-control" name="building"
                            value="{{$office->building}}" autofocus>
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
                        <label for="note">備考</label>
                        <textarea id="note" class="form-control" name="note">{{$office->note}}</textarea>
                    </div>
                    <div class="text-center">
                        <button :disabled="isValid == false" type="submit" name="submit"
                            class="btn btn-primary">変更</button>
                    </div>
                </form>
            </div>
        </div>
    </offices-edit>
</div>
<!-- </div> -->
@endsection

@section('js')
<script>
    new Vue({
        el: '#offices_edit'
    })

</script>
@endsection
