Vue.component('detailed-boards-index',{
    props: {
        propsHard: Object,
        propsAdjust: Object,
        propsShip: Object
    },
    data() {
        return {
            hard: [],
            checkedHardClassifications: "all",
            checkedAdjustmentClassifications: "all",
            checkedShipmentClassifications: "all",
            columns: [
                {
                    field: "id",
                    title: "ID",
                    sortable: true,
                    formatter: function(value, row, index) {
                        return '在P-' + value
                    }
                },
                {
                    field: "mounted_board.print_circuit_board.thing.model_no",
                    title: "基板名",
                    sortable: true,
                    filterControl: "input",
                    //visible: false,
                    formatter: function (value, row, index) {
                        return '<a href="./detailed_boards/' + row.id + '">' + value + '</a>'
                    }
                },
                {
                    field: "mounted_board.version",
                    title: "バージョン",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: "hardware_operation",
                    title: "HW動作",
                    sortable: true,
                    filterControl: "select",
                    formatter: (value, row, index) => {
                        const hardArray = this.hardArray
                        return hardArray[value]
                    }
                },
                {
                    field: "adjustment_calibration",
                    title: "調整校正",
                    sortable: true,
                    filterControl: "select",
                    formatter: (value, row, index) => {
                        const adjustArray = this.adjustArray
                        return adjustArray[value]
                    }
                },
                {
                    field: "shipment",
                    title: "出荷",
                    sortable: true,
                    filterControl: "select",
                    formatter: (value, row, index) => {
                        const shipArray = this.shipArray
                        return shipArray[value]
                    }
                },
                {
                    field: "software_version",
                    title: "ソフトウェア・バージョン",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: "production_date",
                    title: "製造日",
                    sortable: false,
                    filterControl: "input"
                },
                {
                    field: "user_id",
                    title: "担当者",
                    filterControl: "input",
                    visible: false
                },
                {
                    field: "lot_amount",
                    title: "Lot_no",
                    filterControl: "input",
                    visible: false
                },
                {
                    field: "depository",
                    title: "保管場所",
                    filterControl: "input",
                    sortable: true
                },
                {
                    field: "note",
                    title: "備考",
                    visible: false,
                    filterControl: "input"
                }
            ]
        }
    },
    watch: {
        checkedHardClassifications: function (after, before) {
            this.filter()
        },
        checkedAdjustmentClassifications: function (after, before) {
            this.filter()
        },
        checkedShipmentClassifications: function (after, before) {
            this.filter()
        },
    },
    created(){
        this.hardArray = this.propsHard
        this.adjustArray = this.propsAdjust
        this.shipArray = this.propsShip
    },
    methods: {
        filter() {
            const hardChecked = this.checkedHardClassifications
            const adjustmentChecked = this.checkedAdjustmentClassifications
            const shipmentChecked = this.checkedShipmentClassifications
            this.$refs.table.filterBy(
                { yourFiltersHere: "itsTheSecondParameter" },
                {
                    filterAlgorithm: (row, filters)=>{
                        if (hardChecked == "all") {
                            if (adjustmentChecked == "all") {
                                if (shipmentChecked == "notShipped") {
                                    return (row.shipment == "Yet" || row.shipment == "Use" ) ? true : false
                                } else {
                                    return (shipmentChecked == row.shipment || shipmentChecked == "all") ? true : false
                                }
                            } else {
                                return (adjustmentChecked == row.adjustment_calibration && (row.shipmentChecked == row.shipment || shipmentChecked == "all")) ? true : false
                            }
                        } else {
                            if(adjustmentChecked == "all"){
                                if (shipmentChecked == "notShipped") {
                                    return (hardChecked == row.hardware_operation && (row.shipment == "Done" || row.shipment == "Use")) ? true : false
                                } else {
                                    return (hardChecked == row.hardware_operation && (shipmentChecked == row.shipment || shipmentChecked == "all")) ? true : false
                                }
                            } else {
                                return (hardChecked == row.hardware_operation && adjustmentChecked == row.adjustment_calibration && (row.adjustmentshipmentChecked == row.shipment || shipmentChecked == "all")) ? true : false
                            }
                        }
                    }
                })
        }
    }
})
