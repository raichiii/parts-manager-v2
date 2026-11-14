@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('companies'))

@section('content')

<div id="companies_index">
    <div class="card">
        <div class="card-header text-center">
            <h5>会社一覧</h5>
        </div>
        <div class="col-12 text-right">
            <a href="{{route('companies.create')}}">
                <span class="h6 align-text-bottom text-right">
                    <i class="fas fa-plus-circle"></i>
                    会社の新規作成
                </span>
            </a>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="control-label col-md-2">分類</label>
                <div class="col-md-10">
                    @foreach($classifications as $classification)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" id="classification_{{$classification->id}}" type="checkbox"
                            value="{{$classification->id}}" v-model="checkedClassifications"
                            style="width: 30px; height: 15px;">
                        <label class="form-check-label" for="classification_{{$classification->id}}">
                            {{$classification->alias_name}}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-md-2" for="classification-method">分類の検索法</label>
                <div class="col-md-2">
                    <select class="form-control" id="classification-method" v-model="selectedClassificationMethod">
                        <option default>OR</option>
                        <option>AND</option>
                    </select>
                </div>
            </div>
            <bootstrap-table-component ref="table" :props-data="{{$companies}}" :props-columns="columns" @row-click="rowClick">
            </bootstrap-table-component>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#companies_index',
        data() {
            return {
                checkedClassifications: [],
                selectedClassificationMethod: "OR",
                columns: [{
                        field: "id",
                        title: "ID",
                        sortable: true
                    },
                    {
                        field: "name",
                        title: "会社名",
                        filterControl: "input",
                        sortable: true,
                        visible: true,
                        formatter: function (value, row, index) {
                            return '<span onclick="Com(event)">' + value + '</span>';
                        }
                    },
                    {
                        field: "furigana",
                        title: "会社名(ふりがな)",
                        filterControl: "input",
                        sortable: true,
                        visible: false
                    },
                    {
                        field: "english_name",
                        title: "会社名(英語表記)",
                        filterControl: "input",
                        sortable: true,
                        visible: false
                    },
                    {
                        field: "short_name",
                        title: "呼称",
                        filterControl: "input",
                        sortable: true,
                        visible: false,
                        formatter: function (value, row, index) {
                            if (value !== null) {
                                return '<a href="{{route('companies.index')}}/' + row.id + '">' + value + '</a>';
                            }else{
                                return '<a href="{{route('companies.index')}}/' + row.id + '">' + row.name + '</a>';
                            }

                        }
                    },
                    {
                        field: "short_english_name",
                        title: "呼称(英語名)",
                        sortable: true,
                        filterControl: "input",
                    },
                    {
                        field: "urls",
                        title: "URL",
                        sortable: false,
                        filterControl: "input",
                        visible: false,
                        formatter: function (value, row, index) {
                            if (value.length == 0) {
                                return "-";
                            }
                            var list = '<ul>';
                            for (var i = 0; i < value.length; i++) {
                                list += '<li>' + value[i] + '</li>';
                            }
                            return list += '</ul>';
                        }
                    },
                    {
                        field: "offices",
                        title: "事務所",
                        sortable: false,
                        filterControl: "input",
                        formatter: function (value, row, index) {
                            if (value.length == 0) {
                                return "-";
                            }
                            const offices = value;
                            var list = '<!--' + JSON.stringify(offices) + '--><ul style="padding-left: 20px;">'
                            Object.keys(offices).forEach(function (key) {
                                list += '<li><a href="{{route('companies.index')}}/' +
                                    row.id + '/offices/' + offices[key].id + '">' + offices[key]
                                    .name + '</a></li>';
                            })
                            return list += '</ul>';
                        }
                    },
                    {
                        field: "customers",
                        title: "顧客",
                        sortable: false,
                        filterControl: "input",
                        formatter: function (value, row, index) {
                            if (value.length == 0) {
                                return "-";
                            }
                            const customers = value
                            var list = '<!--' + JSON.stringify(customers) + '--><ul style="padding-left: 20px;">'
                            Object.keys(customers).forEach(function (key) {
                                list += '<li><a href="{{route('companies.index')}}/' +
                                    row.id + '/offices/' + customers[key].office_id +
                                    '/customers/' +
                                    customers[key].id + '">' + customers[key].name +
                                    '</a></li>';
                            })
                            return list += '</ul>';
                        }
                    },
                    {
                        field: "classifications",
                        title: "分類",
                        sortable: false,
                        visible: false,
                        formatter: function (value, row, index) {
                            if (value.length == 0) {
                                return "-";
                            }
                            const classifications = value
                            var list = '<ul>';
                            Object.keys(classifications).forEach(function (key) {
                                list += '<li>' + classifications[key].alias_name + '</li>';
                            })
                            return list += '</ul>';
                        }
                    },
                    {
                        field: "note",
                        title: "備考",
                        sortable: false,
                        visible: false,
                        filterControl: "input"
                    },
                ]
            }
        },
        watch: {
            checkedClassifications: function (after, before) {
                this.filter();
            },
            selectedClassificationMethod: function(after, before) {
                this.filter();
            },
        },
        mounted(){
            // $('table').hide();
            // $('input.search-input:first').keyup(function(){
            //     var value = $(this).val();
            //     if(value == ""){
            //         $('table').hide();
            //     }else{
            //         $('table').show();
            //     }
            // })
        },
        methods: {
            filter() {
                const arrayChecked = this.checkedClassifications.map(str => parseInt(str));
                this.$refs.table.filterBy(
                    { yourFiltersHere: "itsTheSecondParameter" },
                    {
                        filterAlgorithm: (row, filters)=>{
                            if(arrayChecked.length == 0){
                                return true;
                            }
                            const classifications = row.classifications;
                            if(classifications.length == 0){
                                return false;
                            }
                            const arrayClassifications = Object.keys(classifications).map((key)=>{
                                return classifications[key].id;
                            });
                            const productSet = arrayClassifications.filter((val)=>{
                                return arrayChecked.indexOf(val) != -1;
                            });
                            if(this.selectedClassificationMethod == "AND"){
                                return (arrayChecked.sort().toString() == productSet.sort().toString()) ? true : false;
                            }else if(this.selectedClassificationMethod == "OR"){
                                return (productSet.length > 0) ? true : false;
                            }
                        }
                    }
                );
            },
            rowClick(...args) {
                const [row, elemet, field] = args;
                if(!(field === 'offices' || field === 'customers')){
                    window.location.href = "/address/companies/" + row.id;
                }

            },
        }
    })
    const Com = (e) => {
        console.log(e.target);
    }

</script>
@endsection
