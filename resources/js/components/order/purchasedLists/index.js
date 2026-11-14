Vue.component('purchased-lists-index', {
    props: {
        propsLotUnitArray: Object
    },
    data() {
        return {
            columns: [{
                field: "checkbox",
                title: '全選択',
                formatter: function (value, row, index) {
                    return '<div class="text-center pt-1"> \
                        <input type="checkbox" class="check-part" name="ids[]" value="' + row.id + '"></div>'
                }
            },
            {
                field: "id",
                title: 'ID',
                sortable: true,
            },
            {
                field: "job_id",
                title: "JobNo",
                filterControl: "input",
                sortable: true,
                formatter: (value, row, index) => '<div class="popper-ref"><a href="/order/jobs/' + row.job_id + '">' + row.job.number + '</a><span class="spacer-inline"></span> \
                    <span class="popper-txt">' + row.job.name + '</span></div>',
            },
            {
                field: "thing.model_no",
                title: "品名",
                filterControl: "input",
                sortable: true,
                formatter: function (value, row, index) {
                    if (row.print_circuit_board) {
                        return '基板'
                    } else if (row.part) {
                        return row.part.name
                    } else if (row.other) {
                        return row.other.name
                    }
                }
            },
            {
                field: "thing",
                title: "型番",
                filterControl: "input",
                sortable: true,
                formatter: function (value, row, index) {
                    if (row.thing_id !== null) {
                        return value.model_no
                    } else {
                        try {
                            return '<div class="text-danger">' + row.unregistered_models.find(el => el.type == 'ModelNo').name + '</div>'
                        } catch {
                            //
                        }
                    }
                }
            },
            {
                field: "lot_size",
                title: "入り数",
                sortable: true,
                filterControl: "input",
            },
            {
                field: "lot_amount",
                title: "袋数",
                sortable: true,
                filterControl: "input",
                formatter: (value, row, index) => {
                    return value + this.propsLotUnitArray[row.lot_unit_id]
                }
            },
            {
                field: "supplier",
                title: '仕入れ先',
                filterControl: "input",
                sortable: true,
                formatter: (value, row, index) => {
                    if(value !== null) {
                        return '<div class="table-supplier">' + value.name + '</div>'
                    }else{
                        try {
                            return '<div class="text-danger table-supplier">' + row.unregistered_models.find(el => el.type == 'Supplier').name + '</div>'
                        }catch{
                            return '<div class="table-supplier">-</div>'
                        }
                    }
                }
            },
            {
                field: "unit_price",
                title: "袋単価",
                sortable: true,
                filterControl: "input",
            },
            {
                field: "depository",
                title: "棚",
                sortable: true,
                filterControl: "input",
            },
            {
                field: "purchase_at",
                title: "希望納期",
                sortable: true,
                filterControl: "input",
                formatter: (value, row, index) => {
                    if (value === null) {
                        return null
                    }
                    const at = new Date(value)
                    const month = at.getMonth() + 1
                    return at.getFullYear() + "/" + month + "/" + at.getDate()
                }
            },
            {
                field: "requested_user.name",
                title: "依頼者",
                filterControl: "input",
                sortable: true,
                visible: false,
            },
            {
                field: "requested_at",
                title: "依頼日",
                filterControl: "input",
                sortable: true,
                visible: false,
            },
            {
                field: "ordered_user.name",
                title: "発注者",
                filterControl: "input",
                sortable: true,
                visible: false,
            },
            {
                field: "ordered_at",
                title: "発注日",
                filterControl: "input",
                sortable: true,
                visible: false,
            },
            {
                field: "received_user.name",
                title: "受領者",
                filterControl: "input",
                sortable: true,
                visible: false,
            },
            {
                field: "created_at",
                title: "受領日",
                filterControl: "input",
                sortable: true,
                visible: false,
            },]
        }
    },

    methods: {
        rowClick(...args) {
            const [row, elemet, field] = args
            if (field !== 'checkbox') {
                window.location.href = "/order/purchased_lists/" + row.id + "/edit"
            }
        },
        detailFormatter(index, row, element) {
            let details = []
            const requested_at = this.dateFormat(row.requested_at)
            const ordered_at = this.dateFormat(row.ordered_at)
            const created_at = this.dateFormat(row.created_at)
            details.push("<p><b>")
            details.push("　　依頼者：" + row.requested_user.name)
            details.push("　　依頼日：" + requested_at)
            details.push("　　発注者：" + row.ordered_user.name)
            details.push("　　発注日：" + ordered_at)
            details.push("　　受領者：" + row.received_user.name)
            details.push("　　受領日：" + created_at)
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
