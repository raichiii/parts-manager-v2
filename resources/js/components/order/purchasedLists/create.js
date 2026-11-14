Vue.component('order-lists-create', {
    props: {
        propsTypes: Object,
    },
    data() {
        return {
            suggestionInput: {
                type: "",
                maker: "",
                supplier: "",
            },
            results: {
                type: [],
                maker: [],
                supplier: [],
            },
            open: {
                type: false,
                maker: false,
                supplier: false,
            },
            types: [],
            selectedType: '',
        }
    },
    created() {
        const types = this.propsTypes
        this.types = Object.entries(types).map(([key, value]) => ({ 'name': value, 'value': key }))
    },
    methods: {
        suggestionClick(key, index) {
            this.suggestionInput[key] = this.suggestionList(key, index)
            this.open[key] = false
        },
        getResults(key, results) {
            this.results[key] = results
            this.open[key] = true
        },
        suggestionList(key, index) {
            return this.results[key][index].name
        }
    }
})
