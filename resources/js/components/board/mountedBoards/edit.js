Vue.component('mounted-boards-edit', {
    props: {
        propsParts: Array,
    },
    data() {
        return {
            edit: {
                parts: [],
            },
            suggestionInput: "",
            results: [],
            open: false,
        }
    },
    created() {
        if (this.propsParts.length > 0) {
            this.propsParts.forEach(e => {
                this.edit.parts.push({
                    id: e.id,
                    name: e.name,
                    model_no: e.thing.model_no,
                    maker: e.maker,
                    referenceNumber: e.pivot.reference_number,
                    isMounted: e.pivot.is_mounted,
                })
            })
        }
    },
    methods: {
        suggestionClick(index) {
            this.suggestionInput = ''
            this.open = false
            this.results = this.results[index]
            const additionalInput = {
                id: this.results.id,
                name: this.results.name,
                model_no: this.results.thing.model_no,
                maker: this.results.maker,
                referenceNumber: '',
                isMounted: 0,
            }
            this.edit.parts.push(additionalInput)
        },
        getResults(results) {
            this.results = results
            this.open = true
        },
        suggestionList(index) {
            return this.results[index].name + " [" + this.results[index].thing.model_no + "]"
        },
        copyInputForParts(index) {
            const additionalInput = {
                id: this.edit.parts[index].id,
                name: this.edit.parts[index].name,
                model_no: this.create.parts[index].model_no,
                maker: this.edit.parts[index].maker,
                referenceNumber: '',
                isMounted: 0,
            }
            this.edit.parts.push(additionalInput)
        },
        deleteInputForParts(id) {
            this.edit.parts.splice(id, 1)
        },
        deleteAllParts() {
            this.edit.parts = []
        },
        getIsMountedName(index) {
            return 'is_mounted[' + index + ']'
        }
    }
})
