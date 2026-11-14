@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('customer.edit',$routeCompany,$routeOffice,$customer))

@section('content')
<!-- <div class="container"> -->
<div id="customers_edit">
    <customers-edit inline-template :props-customer='@json($customer)'>
        <div class="card">
            <div class="card-header text-center">
                <h5>顧客編集</h5>
            </div>
            <div class="card-body">
                <form action="{{route('customers.update', [$routeCompany, $routeOffice, $customer]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="select-company">会社</label>
                        <select id="select-company" class="custom-select" name="company_id">
                            <option value="{{$routeCompany->id}}" selected>{{$routeCompany->full_name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="select-office">会社</label>
                        <select id="select-office" class="custom-select" name="office_id">
                            <option value="{{$routeOffice->id}}" selected>{{$routeOffice->name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">顧客名</label>
                        <input :class="errorClassObject('name')" v-model="edit.name" id="name" type="text"
                            class="form-control" name="name" required autofocus>
                        <div class="invalid-feedback">
                            顧客名を入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="furigana">顧客名(ふりがな)</label>
                        <input :class="errorClassObject('furigana')" v-model="edit.furigana" id="furigana" type="text"
                            class="form-control" name="furigana" autofocus>
                        <div class="invalid-feedback">
                            ひらがなで入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="english-name">顧客名(英語表記)</label>
                        <input :class="errorClassObject('englishName')" v-model="edit.englishName" id="english_name"
                            type="text" class="form-control" name="english_name" autofocus>
                        <div class="invalid-feedback">
                            英数字で入力してください
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="department">部署</label>
                        <input id="department" type="text" class="form-control" name="department"
                            value="{{$customer->department}}" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="position">肩書き</label>
                        <input id="position" type="text" class="form-control" name="position"
                            value="{{$customer->position}}" autofocus>
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
                        <textarea id="note" class="form-control" name="note">{{$customer->note}}</textarea>
                    </div>
                    <div class="text-center">
                        <button :disabled="isValid == false" type="submit" name="submit"
                            class="btn btn-primary">変更</button>
                    </div>
                </form>
            </div>
        </div>
    </customers-edit>
</div>
<!-- </div> -->
@endsection

@section('js')
<script>
    new Vue({
        el: '#customers_edit'
    })

</script>
@endsection
