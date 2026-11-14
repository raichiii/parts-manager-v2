const {
    add
} = require("lodash")

Vue.component('mounted-boards-create', {
    props: {
        propsMountedBoards: Array,
    },
    data() {
        return {
            create: {
                parts: [],
            },
            selectedVersionType: 'new',
            mountedBoards: [],
            suggestionInput: "",
            results: [],
            open: false,
        }
    },
    created() {
        const mountedBoards = this.propsMountedBoards
        Object.keys(mountedBoards).forEach((key)=>{
            this.mountedBoards.push(
                {
                    id: mountedBoards[key].id,
                    versionName: mountedBoards[key].version_name,
                    version: mountedBoards[key].version
                }
            )
        })
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
            this.create.parts.push(additionalInput)
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
                id: this.create.parts[index].id,
                name: this.create.parts[index].name,
                model_no: this.create.parts[index].model_no,
                maker: this.create.parts[index].maker,
                referenceNumber: '',
                isMounted: 0,
            }
            this.create.parts.push(additionalInput)
        },
        deleteInputForParts(id) {
            this.create.parts.splice(id, 1)
        },
        getIsMountedName(index) {
            return 'is_mounted[' + index + ']'
        }
    }
})
