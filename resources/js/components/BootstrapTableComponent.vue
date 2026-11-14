<template>
    <div>
        <bootstrap-table
            ref="table"
            :data="data"
            :options="options"
            :columns="columns"
            @on-click-row="onClickRow"
        />
    </div>
</template>

<script>
import BootstrapTable from "bootstrap-table/dist/bootstrap-table-vue.min.js";
export default {
    props: {
        propsData: Array,
        propsColumns: Array,
    },
    components: {
        "bootstrap-table": BootstrapTable
    },
    data() {
        return {
            data: [],
            options: {
                search: true,
                searchAlign: "left",
                detailView: false,
                pagination: true,
                pageSize: 50,
                pageList: [50, 100, 500, "All"],
                showColumns: true,
                showPrint: true,
                showExport: true,
                exportDataType: "all",
                filterControl: true,
                toolbar: "#toolbar",
                clickToSelect: true,
                idField: "id",
                selectItemName: "id",
                resizable: true,
                locale: "ja-JP"
            },
            columns: []
        };
    },
    created() {
        this.columns = this.propsColumns;
        this.data = this.propsData;
    },
    methods: {
        filterBy(filter,options) {
            this.$refs.table.filterBy(filter,options);
        },
        onClickRow(row,$element,field) {
            this.$emit('row-click',row,$element,field);
        },
    }
};
</script>
