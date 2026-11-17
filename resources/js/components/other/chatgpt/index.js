const { default: axios } = require("axios")

const MODE_NORMAL = 0
const MODE_PRESIDENT = 1

Vue.component('chatgpt-index', {
    props: {
        propsChatgptApiUrl: String,
    },
    data() {
        return {
            prompt: "簡単な自己紹介をお願いします。",
            chatHistories: [],
            mode: 1,
            isLoading: false,
            prompt_president: "株式会社フレクトの代表取締役社長 黒川幸治になりきって回答してください。学生起業家でIT企業経営20年。前身IT会社の起業を経て、2005年に株式会社フレクトを設立。代表取締役CEOとしてクラウド事業へ参入とその事業基盤を構築、現在に至るまで当社経営の舵取りを行う。「インターネットを通じてみんなの人生満足を追求する」をミッションに掲げて、当社に関わるステークホルダーへの幸せと価値提供の最大化を目指す。",
        }
    },
    mounted() {
        this.promptDefaultHeight = this.$refs.prompt.clientHeight
    },
    methods: {
        question() {
            if (this.prompt === "") return
            setTimeout(() => this.changePromptHeight(), 1)
            this.addToTimeline(this.prompt, textRight=true)
            this.chatHistories.push({ speaker: "client", text: this.prompt })
            this.prompt = "" // Delete prompt from textarea
            const preprompt = this.mode === MODE_PRESIDENT? [{ speaker: "client", text: this.prompt_president }] : []
            const prompt = JSON.stringify([...preprompt, ...this.chatHistories])
            axios.post(this.propsChatgptApiUrl,
                { content: prompt },
                { headers: { "Content-Type": "application/json" } }
            ).then(res => {
                this.isLoading = false
                const reply = res.data.content
                this.chatHistories.push({speaker: "client", text: reply})
                this.addToTimeline(reply, textRight=false)
            }).catch(err => {
                this.isLoading = false
                if (err.response.status) {
                    if (err.response.status === 429) {
                        this.addToTimeline("1分ほど時間をおいてから試してください", textRight = false)
                    }
                } else {
                    this.addToTimeline("予期せぬエラーが発生しました。再度お試しください。", textRight=false)
                }
            })
        },
        addToTimeline(text, textRight=false) {
            let newReplyElement = document.createElement("p")
            textRight? newReplyElement.classList.add("chatgpt-text-right") : newReplyElement.classList.add("chatgpt-text-left")
            ac = this.$refs.chatHistory.appendChild(newReplyElement)
            let count = 0
            this.writeChatgpt(text, ac, count)
        },
        /**
         * 複数チャットが書き込まれている最中でもバグが起こらないようになっている
         */
        writeChatgpt(text, ac, count) {
            // 同時に複数のチャットで変数を共有しないように
            // chatgptIntervalはconstまたはletにする必要がある
            const chatgptInterval = setInterval(() => {
                let writtenChar = text.substr(count, 1)
                if (writtenChar === " ") {
                    writtenChar = text.substr(count, 2).replace(/ /g, "\u00A0")
                    count++
                }
                ac.innerText += writtenChar
                count++
                if (count > text.length) {
                    handleEnd()
                }
                else if ([",", "、"].includes(writtenChar)) {
                    handleComma(text, ac, count)
                }
                else if ([".", "。"].includes(writtenChar)) {
                    handlePeriod(text, ac, count)
                }
            }, 30)

            // chatgptIntervalはconst（this経由で他のメソッドに渡せない）なので、内部の関数で処理
            // アロー関数型で書かないとthisのスコープが変わる
            const handleEnd = () => {
                clearInterval(chatgptInterval)
            }
            const handleComma = (...args) => {
                clearInterval(chatgptInterval)
                setTimeout(() => {
                    this.writeChatgpt(...args)
                }, 100)
            }
            const handlePeriod = (...args) => {
                clearInterval(chatgptInterval)
                setTimeout(() => {
                    this.writeChatgpt(...args)
                }, 300)
            }
        },
        changePromptHeight() {
            this.$refs.prompt.style.height = this.promptDefaultHeight + 'px'
            const sh = this.$refs.prompt.scrollHeight
            this.$refs.prompt.style.height = sh + 10 + 'px'
        },
        placeholderByMode() {
            return [
                "Windowsパソコンを再起動するショートカットを教えてください",
                "御社の将来の目標は何ですか？",
            ][this.mode]
        }
    }
})
