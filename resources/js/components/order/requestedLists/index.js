Vue.component('requested-lists-index', {
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
                field: "thing_id",
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
                field: "model_no",
                title: "型番",
                filterControl: "input",
                sortable: true,
                formatter: function (value, row, index) {
                    if (row.thing_id !== null) {
                        return row.thing.model_no
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
                field: "supplier_id",
                title: '<div class="text-success">仕入れ先</div>',
                filterControl: "input",
                sortable: true,
                formatter: (value, row, index) => {
                    if(value !== null) {
                        return '<div class="table-supplier">' + row.supplier.name + '</div>'
                    }else{
                        try {
                            return row.unregistered_models.find(el => el.type == 'Supplier').name
                        }catch{
                            return '<div class="table-supplier">-</div>'
                        }
                    }
                }
            },
            {
                field: "lot_size",
                title: "入り数",
                filterControl: "input",
                sortable: true,
            },
            {
                field: "lot_amount",
                title: "袋数",
                filterControl: "input",
                sortable: true,
                formatter: (value, row, index) => {
                    return value + this.propsLotUnitArray[row.lot_unit_id]
                }
            },
            {
                field: "unit_price",
                title: "袋単価",
                filterControl: "input",
                sortable: true,
            },
            {
                field: "depository",
                title: "棚",
                filterControl: "input",
                sortable: true,
            },
            {
                field: "purchase_at",
                title: "希望納期",
                filterControl: "input",
                sortable: true,
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
                field: "created_at",
                title: "依頼日",
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
                window.location.href = "/order/requested_lists/" + row.id + "/edit"
            }
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
