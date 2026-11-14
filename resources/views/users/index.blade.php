@extends('layouts.app')

@section('content')
<!-- <div class="container"> -->

<div class="card" id="users-index">
    <div class="card-header text-center">
        <h5>ユーザー一覧</h5>
    </div>
    <div class="mt-3">
        <searchbar-component get-url="{{route('users.get')}}" fetch-url="{{route('users.search')}}" @results="getUsers">
        </searchbar-component>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead class="border-bottom border-danger">
                    <tr>
                        <th style="width: 30%">ユーザー名</th>
                        <th style="width: 30%">表示名</th>
                        <th style="width: 35%">メールアドレス</th>
                        <th style="width: 20%">ロール</th>
                        <th style="width: 15%;text-align: center">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="" v-for="user in users" :key="user.id">
                        <td>@{{user.username}}</td>
                        <td>@{{user.name}}</td>
                        <td>@{{user.email}}</td>
                        <td nowrap>
                            <div v-for="role in user.roles" :key="role.id">
                                @{{role.alias_name}}
                            </div>
                        </td>
                        <td nowrap style="text-align: center">
                            <a class="btn btn-outline-primary" :href="route + '/' + user.id" role="button">
                                詳細
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- </div> -->
@endsection

@section('css')
<style>
    .pagination {
        justify-content: center;
    }

</style>
@endsection

@section('js')
<script>
    new Vue({
        el: '#users-index',
        data() {
            return {
                users: [],
                route: '',
            }
        },
        mounted: function () {
            this.route = `{{route('users.index')}}`
            axios.get(`{{route('users.get')}}`)
                .then(res => {
                    this.users = res.data
                })
                .catch(err => {
                    console.log(err);
                })
        },
        methods: {
            getUsers: function (results) {
                this.users = results;
            }
        }
    })

</script>
@endsection
