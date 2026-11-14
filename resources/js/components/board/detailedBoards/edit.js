const {
    add
} = require("lodash")
import regx from '../../const/regularExpression'

Vue.component('detailed-boards-edit', {
    props: {
        propsDetailedBoard: Object,
        propsVersions: Array,
    },
    data() {
        return {
            detailedBoard: '',
            suggestionInput: "",
            edit: {
                lotNumber: '',
            },
            results: [],
            versions: [],
            open: false,
        }
    },
    created() {
        this.detailedBoard = this.propsDetailedBoard
        this.edit.lotNumber = this.detailedBoard.lot_amount
        this.suggestionInput = this.detailedBoard.mounted_board.print_circuit_board.thing.model_no
        this.results = this.detailedBoard.mounted_board.print_circuit_board
        const versions = this.propsVersions
        Object.keys(versions).forEach((key)=> {
            if (versions[key] == this.detailedBoard.mounted_board.version) {
                this.versions.push({ id: key, version: versions[key], initial: true})
            } else {
                this.versions.push({ id: key, version: versions[key]})
            }
        })
    },
    computed: {
        validation() {
            const edit = this.edit
            return {
                lotNumber: (!edit.lotNumber || regx.numeric.test(edit.lotNumber)),
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
            return this.results[index].name
        }
    }
})
