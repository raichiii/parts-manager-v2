Vue.component('detailed-boards-kensaku', {
    props: {
        propsColNames: Object,
        propsUrl: String,
        propsSearchUrl: String,
    },
    data() {
        return {
            suggestionInput: '',
            results: [],
            rule: 0,
        }
    },
    created() {
        this.colNames = this.propsColNames
        this.url = this.propsUrl
        this.searchUrl = this.propsSearchUrl
        this.debouncedFetch = _.debounce(this.fetch, 300)
    },
    mounted() {
        this.data = []
        axios.get(`${this.url}`)
        .then(res=>{
            this.data = res.data
        })
        .catch(err=>{
            console.log(err)
        })
        this.$refs.focus.focus()
    },
    methods: {
        fetch() {
            this.results = []
            if (!this.suggestionInput) return

            const SUGGESTIONINPUT = this.suggestionInput.toUpperCase()
            this.data.map((row, i) => {
                let suggested = false
                let cells = []
                row.split(',').map(cell => cells.push(cell.replace(/[\"]/g,"")))
                const id = cells[Object.keys(this.colNames).indexOf('id')]
                const modelNo = cells[Object.keys(this.colNames).indexOf('model_no')]
                if (this.rule == 0) suggested = id == SUGGESTIONINPUT
                else if (this.rule == 1) suggested = modelNo ? ~modelNo.indexOf(SUGGESTIONINPUT) : false
                else if (this.rule == 2) suggested = ~row.toUpperCase().indexOf(SUGGESTIONINPUT)
                if (suggested)
                {
                    let priority = 0
                    if (SUGGESTIONINPUT == id) priority += 1
                    const createdAt = cells[Object.keys(this.colNames).indexOf('created_at')]
                    const note = cells[Object.keys(this.colNames).indexOf('note')].replace(/\\n/g, '<br />')
                    let infos = []
                    Object.keys(this.colNames).map((key, idx) => {
                        if (!~['id', 'model_no', 'created_at', 'note'].indexOf(key)) infos.push(`${this.colNames[key]}: ${cells[idx]}`)
                    })
                    this.results.push({
                        id: id,
                        modelNo: modelNo,
                        createdAt: createdAt,
                        infos: infos,
                        note: note,
                        priority: priority,
                    })
                }
            })
            this.results.sort((a, b) => a.priority !== b.priority? b.priority - a.priority : b.id - a.id)
        },
        clickPart(e) {
            const part = e.target.innerText
            const searchUrl = this.searchUrl.replace(/\[part\]/g, part)
            console.log(searchUrl)
            window.open(searchUrl)
        },
    }
})
