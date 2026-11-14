Vue.component('jobs-edit', {
    props: {
        propsJob: Object,
    },
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
    created(){
        const Job = this.propsJob
        this.suggestionInput.requester = Job.requester.name
        this.suggestionInput.deliveryDestination = Job.delivery_destination.name
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
            return this.results[key][index].name
        }
    }
})
