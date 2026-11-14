Vue.component('jobs-create', {
    data() {
        return {
            suggestionInput: {
                requester: "",
                deliveryDestination: "",
            },
            results: {
                requester: [],
                deliveryDestination: [],
            },
            open: {
                requester: false,
                deliveryDestination: false,
            },
            query: {
                type: 'requester',
            },
        }
    },
    methods: {
        suggestionClick(key, index) {
            this.suggestionInput[key] = this.results[key][index].name
            this.open[key] = false
        },
        getResults(key, results) {
            this.results[key] = results
            this.open[key] = true
        },
        suggestionList(key, index) {
            return this.results[key][index].name + " [" + this.results[key][index].full_name + "]"
        }
    }
})
