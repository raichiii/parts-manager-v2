Vue.component('parts-kensaku', {
    props: {
        propsUrl: String,
        propsGetUrl: String,
        propsSearchUrl: String,
    },
    data() {
        return {
            suggestionInput: '',
            results: [],
            boardJobs: [],
            boardNames: [],
            parts: [],
            listDatas: [],
            Ps: [],
            PSet: [],
            checkAllP: true,
            checkedPs: [],
            maxLenP: 0,
            boardJob: [],
        }
    },
    created() {
        this.url = this.propsUrl
        this.getUrl = this.propsGetUrl
        this.searchUrl = this.propsSearchUrl
        this.debouncedFetch = _.debounce(this.fetch, 300)
    },
    mounted() {
        axios.get(`${this.url}`)
            .then(res => {
                this.boardJobs = res.data
            })
            .catch(err => {
                console.log(err.response.data.message)
            })
        axios.get(`${this.getUrl}`)
            .then(res => {
                this.boardNames = res.data.boardNames
                this.parts = res.data.parts
                this.listDatas = res.data.listDatas
                this.Ps = res.data.Ps
                this.PSet = [...new Set(this.Ps)]
                this.checkedPs = this.PSet
                this.maxLenP = this.PSet.length
                this.boardJob = res.data.boardJob
            })
            .catch(err => {
                console.log(err.response.data.message)
            })
        this.$refs.focus.focus()
    },
    methods: {
        fetch() {
            this.results = []
            if (!this.suggestionInput) return

            const SUGGESTIONINPUT = this.suggestionInput.toUpperCase()
            this.listDatas.map((listData, i) => {
                if (~listData.toUpperCase().indexOf(SUGGESTIONINPUT) && ~this.checkedPs.indexOf(this.Ps[i])
                ) {
                    const part = this.parts[i]
                    let infos = []
                    let boards = []
                    const classification = part.classification ? ` (${part.classification})` : ''
                    const credibility = part.credibility ? '' : '(?)'
                    part.maker ? infos.push(`@${part.maker}`) : null
                    part.name ? infos.push(`${part.name}${classification}`) : null
                    part.pno2 ? infos.push(`P番号: ${part.pno2}`) : null
                    part.stock ? infos.push(`在庫: ${part.stock}${credibility}`) : null
                    part.status ? infos.push(`ステータス: ${part.status}`) : null
                    Object.keys(part).map(key => {
                        if (~['id', 'model_no', 'sum', 'credibility', 'stock', 'pno', 'pno1', 'pno2', 'p',
                            'name', 'classification', 'maker', 'status'].indexOf(key)) return
                        if (part[key]) boards.push([this.boardNames[key], part[key]])
                    })
                    boards.sort()
                    this.results.push({
                        model_no: part.model_no,
                        infos: [...infos],
                        boards: [...boards],
                    })
                }
            })
            this.results.sort((a, b) => a.model_no - b.model_no)
        },
        toggleCheckAllP() {
            if (this.checkAllP && this.checkedPs.length != this.maxLenP) this.checkedPs = [...new Set(this.Ps)]
            else if (!this.checkAllP && this.checkedPs.length != 0) this.checkedPs = []
            this.fetch()
        },
        toggleCheckP() {
            if (this.checkAllP && this.checkedPs.length < this.maxLenP ||
                !this.checkAllP && this.checkedPs.length == this.maxLenP) {
                this.checkAllP = !this.checkAllP
            }
            this.fetch()
        },
        clipLandiskJob(e) {
            const board = e.target.innerText
            const job = this.boardJob[board]
            if (!job) {
                this.alertForClick("基板マスタに存在しない基板名です", "alert-warning")
                return
            }
            const jobNo = (e.ctrlKey || e.metaKey) ? this.boardJob[board][1] : this.boardJob[board][0]
            if (!jobNo) {
                const RJobOrNone =  (e.ctrlKey || e.metaKey) ? "量産" : ""
                this.alertForClick(`${RJobOrNone}Job No.は未登録です`, "alert-warning")
                return
            }
            if (!this.boardJobs.length) {
                this.alertForClick(`Job No.：${jobNo}\nランディスクに接続されていません`, "alert-warning")
                return
            }
            let jobDir
            this.boardJobs.some((val, idx) => {
                if (~val.indexOf(jobNo)) {
                    jobDir = 'file://192.168.0.124/disk/' + val
                    return true
                }
            })
            if (jobDir === undefined) {
                this.alertForClick(`Job No.：${jobNo}\nパスが見つかりませんでした`, "alert-warning")
                return
            }
            let area = document.createElement("textarea")
            area.textContent = jobDir
            document.body.appendChild(area)
            area.select()
            document.execCommand("copy")
            document.body.removeChild(area)
            this.alertForClick(`Job No.：${jobNo}\nコピーしました！${jobDir}`, "alert-success")
        },
        alertForClick(text, alertColor) {
            clearTimeout(this.removeFixedAlert)
            console.log(text)
            let fixedAlert = document.getElementsByClassName("fixed-alert-flg")[0]
            fixedAlert.innerText = text
            fixedAlert.classList.remove("fixed-alert", "alert-warning", "alert-success")
            // アニメーション用のクラスを削除後、一瞬で追加し直してもアニメーションが機能しないため、
            // setTimeoutで少し時間を経過させてから追加し直す
            setTimeout(() => {
                fixedAlert.classList.add("fixed-alert", alertColor)
                this.removeFixedAlert = setTimeout(() => {
                    fixedAlert.classList.remove("fixed-alert")
                }, 5000)
            }, 1)
        },
        clickPart(e) {
            const part = e.target.innerText
            const searchUrl = this.searchUrl.replace(/\[part\]/g, part)
            console.log(searchUrl)
            window.open(searchUrl)
        },
    }
})
