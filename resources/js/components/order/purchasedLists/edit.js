Vue.component('purchased-lists-edit', {
    props: {
        propsPurchasedList: Object,
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
        this.suggestionInput.job = this.propsPurchasedList.job.number
        this.suggestionInput.model_no = this.propsPurchasedList.model_no
        this.suggestionInput.supplier = this.propsPurchasedList.supplierName
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
