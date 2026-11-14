Vue.component('account-lists-edit', {
    props: {
        propsCompany: Object,
    },
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
    created() {
        this.suggestionInput = this.propsCompany.short_name
        this.results = this.propsCompany
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
