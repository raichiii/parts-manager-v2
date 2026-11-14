Vue.component('print-circuit-boards-edit', {
    props: {
        propsMaker: Object,
    },
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
    created() {
        this.suggestionInput = this.propsMaker.name
        this.results = this.propsMaker
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
