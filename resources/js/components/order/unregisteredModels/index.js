Vue.component('unregistered-models-index', {
    data() {
        return {
            columns: [{
                field: "id",
                title: "",
                sortable: true,
            },
            {
                field: "type",
                title: "未登録タイプ",
                filterControl: "input",
                sortable: true,
                formatter: function (value, row, index) {
                    const types = {
                        'ModelNo': '型番',
                        'Supplier': '仕入れ先',
                    }
                    return types[value]
                }
            },
            {
                field: "name",
                title: "未登録名",
                filterControl: "input",
                sortable: true,
            },

            {
                field: "listable_type",
                title: "依頼タイプ",
                filterControl: "input",
                sortable: true,
                formatter: function (value, row, index) {
                    const listable_types = {
                        "App\\Models\\Order\\RequestedList": '注文依頼リスト',
                        'App\\Models\\Order\\OrderedList': '注文済みリスト',
                        'App\\Models\\Order\\PurchasedList': '購入実績',
                    }
                    return listable_types[value]
                }
            },
            {
                field: "listable_id",
                title: "依頼番号",
                filterControl: "input",
                sortable: true,
                visible: false,
            },]
        }
    },
    methods: {
        rowClick(...args) {
            const [row, elemet, field] = args
            const listable_types = {
                'App\\Models\\Order\\RequestedList': 'requested_lists',
                'App\\Models\\Order\\OrderedList': 'ordered_lists',
                'App\\Models\\Order\\PurchasedList': 'purchased_lists',
            }
            if (field !== 'id') {
                window.location.href = "/order/" + listable_types[row.listable_type] + "/" + row.listable_id + "/edit"
            }
        },
        detailFormatter(index, row, element) {
            //
        },
        dateFormat(date_at) {
            const at = new Date(date_at)
            const month = at.getMonth() + 1
            return at.getFullYear() + "/" + month + "/" + at.getDate() + " "
            + at.getHours() + ":" + at.getMinutes()
        }
    }
})
