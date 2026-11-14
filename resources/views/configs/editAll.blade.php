@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('configs.editAll'))

@section('content')
<div id="configs_edit_all">
    <div class="card">
        <div class="card-header text-center">
            <h5>設定</h5>
        </div>
        <div class="card-body">
            <form action="{{route('configs.updateAll')}}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="job-path">JOBパス</label>
                    <div>
                        <span>file://192.168.0.124/disk/</span>
                        <input id="job-path" type="input" class="form-control" name="job_path"
                        value="{{$configs['job_path']}}" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="job-r-path">JOB量産パス</label>
                    <div>
                        <span>file://192.168.0.124/disk/</span>
                        <input id="job-r-path" type="input" class="form-control d-inline-flex" name="job_r_path"
                        value="{{$configs['job_r_path']}}" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="search-address">部品検索用URL（部品名を[part]で指定）</label>
                    <input id="search-address" type="input" class="form-control" name="part_search_address"
                    value="{{$configs['part_search_address']}}" required autofocus>
                </div>
                <div class="form-group">
                    <label for="search-address">詳細基板検索用URL（部品名を[part]で指定）</label>
                    <input id="search-address" type="input" class="form-control" name="detailed_board_search_address"
                    value="{{$configs['detailed_board_search_address']}}" required autofocus>
                </div>
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-primary">変更</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#configs_edit_all',
    })

</script>
@endsection
