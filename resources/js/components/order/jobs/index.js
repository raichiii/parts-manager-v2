Vue.component('jobs-index', {
    data() {
        return {
            columns: [
                {
                    field: "id",
                    title: "ID",
                    sortable: true
                },
                {
                    field: "number",
                    title: "JobNo",
                    filterControl: "input",
                    sortable: true,
                },
                {
                    field: "name",
                    title: "仕事名",
                    filterControl: "input",
                    sortable: true,
                },
                {
                    field: "requester",
                    title: "依頼元",
                    sortable: true,
                    filterControl: "input",
                    formatter: function (value, row, index) {
                        if (value === null) {
                            return null
                        }else if(value.short_name !== null) {
                            return '<a href="/address/companies/' +
                                value.id + '">' + value.short_name + '</a>'
                        }else{
                            return '<a href="/address/companies/' +
                                value.id + '">' + value.name + '</a>'
                        }
                    }
                },
                {
                    field: "delivery_destination",
                    title: "納入先",
                    sortable: true,
                    filterControl: "input",
                    formatter: function (value, row, index) {
                        if (value === null) {
                            return null
                        }else if(value.short_name !== null) {
                            return '<a href="/address/companies/' +
                                value.id + '">' + value.short_name + '</a>'
                        }else{
                            return '<a href="/address/companies/' +
                                value.id + '">' + value.name + '</a>'
                        }
                    }
                },
                {
                    field: "description",
                    title: "詳細",
                    filterControl: "input",
                    sortable: true,
                },
                {
                    field: "created_at",
                    title: "作成日",
                    filterControl: "input",
                    sortable: true,
                    visible: false,
                },
            ],
        }
    },
    methods: {
        rowClick(...args) {
            const [row, elemet, field] = args
            window.location.href = "/order/jobs/" + row.id
        },
    }
})
