Vue.component('ordered-lists-create', {
    props: {
        propsTypes: Object,
    },
    data() {
        return {
            suggestionInput: {
                job: "",
                type: "",
                supplier: "",
            },
            results: {
                job: [],
                type: [],
                supplier: [],
            },
            open: {
                job: false,
                type: false,
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
            if(key === 'job') this.suggestionInput[key] = this.results[key][index].number
            else this.suggestionInput[key] = this.suggestionList(key, index)
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
