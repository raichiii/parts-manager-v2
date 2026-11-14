Vue.component('others-index', {
    data() {
        return {
            columns: [{
                field: "id",
                title: "",
                sortable: true,
            },
            {
                field: "name",
                title: "部品名",
                filterControl: "input",
                sortable: true,
            },
            {
                field: "thing.model_no",
                title: "型番",
                filterControl: "input",
                sortable: true,
            },
            {
                field: "maker",
                title: "メーカー",
                filterControl: "input",
                sortable: true,
                formatter: function (value, row, index) {
                    if(value.short_name){
                        return '<a href="/address/companies/' + value.id + '"><span class="table-supplier">' + value.short_name + '</span></a>'
                    }else{
                        return '<a href="/address/companies/' + value.id + '"><span class="table-supplier">' + value.name + '</span></a>'
                    }
                }
            },
            {
                field: "short_name",
                title: "略名",
                filterControl: "input",
                sortable: true,
            },
            {
                field: "english_name",
                title: "英語名",
                filterControl: "input",
                sortable: true,
            },
            {
                field: "note",
                title: "備考",
                filterControl: "input",
                sortable: true,
            },]
        }
    },
    methods: {
        rowClick(...args) {
            const [row, elemet, field] = args
            window.location.href = "/board/others/" + row.id
        },
        detailFormatter(index, row, element) {
            let details = []
            const created_at = this.dateFormat(row.created_at)
            details.push("<p><b>")
            details.push("　　依頼者：" + row.requested_user.name)
            details.push("　　依頼日：" + created_at)
            details.push("</b></p>")
            return details.join('')
        },
        dateFormat(date_at) {
            const at = new Date(date_at)
            const month = at.getMonth() + 1
            return at.getFullYear() + "/" + month + "/" + at.getDate() + " "
            + at.getHours() + ":" + at.getMinutes()
        }
    }
})
