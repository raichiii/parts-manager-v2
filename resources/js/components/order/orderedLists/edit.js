Vue.component('ordered-lists-edit', {
    props: {
        propsOrderedList: Object,
    },
    data() {
        return {
            suggestionInput: {
                job: "",
                model_no: "",
                supplier: "",
            },
            results: {
                job: [],
                model_no: [],
                supplier: [],
            },
            open: {
                job: false,
                model_no: false,
                supplier: false,
            },
        }
    },
    created() {
        this.suggestionInput.job = this.propsOrderedList.job.number
        this.suggestionInput.model_no = this.propsOrderedList.model_no
        this.suggestionInput.supplier = this.propsOrderedList.supplierName
    },
    methods: {
        suggestionClick(key, index) {
            if(key === 'job') this.suggestionInput[key] = this.results[key][index].number
            else if(key === 'model_no') this.suggestionInput[key] = this.results[key][index].model_no
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
