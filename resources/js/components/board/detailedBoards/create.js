import regx from '../../const/regularExpression'

Vue.component('detailed-boards-create', {
    data() {
        return {
            suggestionInput: "",
            create: {
                lotNumber: "0",
            },
            results: [],
            versions:[],
            open: false,
        }
    },
    computed: {
        validation() {
            const create = this.create
            return {
                lotNumber: (!create.lotNumber || regx.numeric.test(create.lotNumber)),
            }
        },
        isValid() {
            const validation = this.validation
            return Object.keys(validation).every(function (key) {
                return validation[key]
            })
        }
    },
    methods: {
        errorClassObject(key) {
            return {
                'is-invalid': (this.validation[key] == false),
                'is-valid': (this.validation[key] == true)
            }
        },
        suggestionClick(index) {
            this.suggestionInput = this.suggestionList(index)
            this.open = false
            const mountedBoards = this.results[index].mounted_boards
            this.versions = []
            Object.keys(mountedBoards).forEach((key)=> {
                this.versions.push({ id: key, version: mountedBoards[key].version})
            })
        },
        getResults(results) {
            this.results = results
            this.open = true
        },
        suggestionList(index) {
            return this.results[index].thing.model_no
        }
    }
})
