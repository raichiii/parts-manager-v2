Vue.component('text-infos-index', {
    props: {
        propsUrl: String,
    },
    data() {
        return {
            suggestionInput: '',
            results: [],
            andOr: 1,
        }
    },
    created() {
        this.url = this.propsUrl
        this.debouncedFetch = _.debounce(this.fetch, 300)
    },
    mounted() {
        this.data = []
        axios.get(`${this.url}`)
        .then(res=>{
            this.data = res.data
            console.log(this.data)
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
                .replace(/　/g, ' ').split(' ').filter(e=>{return e !== ''})
            this.data.map((row, i) => {
                const title = row[0]
                const content = row[1]
                const suggested = this.andOr?
                    this.andSearch(SUGGESTIONINPUT, title.toUpperCase()) || this.andSearch(SUGGESTIONINPUT, content.toUpperCase()) :
                    this.orSearch(SUGGESTIONINPUT, title.toUpperCase()) || this.orSearch(SUGGESTIONINPUT, content.toUpperCase())
                if (suggested)
                {
                    this.results.push({
                        title: title,
                        content: content.replace(/\n/g, '<br />'),
                    })
                }
            })
        },
        clickPart(e) {
            const part = e.target.innerText
            const searchUrl = this.searchUrl.replace(/\[part\]/g, part)
            console.log(searchUrl)
            window.open(searchUrl)
        },
        andSearch(inputArray, target) {
            return inputArray.every(input => {
                return ~target.indexOf(input)
            })
        },
        orSearch(inputArray, target) {
            return inputArray.some(input => {
                return ~target.indexOf(input)
            })
        }
    }
})
