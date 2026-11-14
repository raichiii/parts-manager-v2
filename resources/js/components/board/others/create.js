Vue.component('others-create', {
    data() {
        return {
            suggestionInput: "",
            results: [],
            open: false,
            query: {
                type: 'maker',
            },
        }
    },
    methods: {
        suggestionClick(index) {
            this.suggestionInput = this.results[index].name
            this.open = false
        },
        getResults(results) {
            this.results = results
            this.open = true
        },
        suggestionList(index) {
            return this.results[index].name + " [" + this.results[index].full_name + "]"
        }
    }
})
