Vue.component('account-lists-create', {
    data() {
        return {
            suggestionInput: "",
            results: [],
            open: false,
            query: {
                type: 'supplier',
            },
        }
    },
    methods: {
        suggestionClick(index) {
            this.suggestionInput = this.results[index].short_name
            this.open = false
        },
        getResults(results) {
            this.results = results
            this.open = true
        },
        suggestionList(index) {
            return this.results[index].short_name + " [" + this.results[index].full_name + "]"
        }
    }
})
