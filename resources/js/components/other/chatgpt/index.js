const { default: axios } = require("axios")

Vue.component('chatgpt-index', {
    props: {
        propsChatgptApiUrl: String,
    },
    data() {
        return {
            prompt: "",
            chatHistories: [],
            mode: 0,
            isLoading: false,
        }
    },
    mounted() {
        this.promptDefaultHeight = this.$refs.prompt.clientHeight
    },
    methods: {
        question() {
            if (this.prompt === "") return
            let prompt = this.prompt
            this.prompt = ""
            setTimeout(() => this.changePromptHeight(), 1)
            this.addChatgptHistory(prompt, textRight=true)
            if (this.mode === 1) {
                this.chatHistories.push(prompt)
                prompt = ""
                this.chatHistories.forEach(chatHistory => {
                    prompt += `\n「${chatHistory}」`
                })
                console.log(prompt)
            }
            this.isLoading = true
            axios.post(this.propsChatgptApiUrl,
                { content: prompt },
                { headers: { "Content-Type": "application/json" } }
            ).then(res => {
                this.isLoading = false
                const reply = res.data.choices[0].message.content
                console.log(reply)
                if (this.mode === 1) {
                    this.chatHistories.push(reply)
                }
                this.addChatgptHistory(reply, textRight=false)
            }).catch(err => {
                this.isLoading = false
                if (err.response.status === 429) {
                    this.addChatgptHistory("1分程時間をおいてから試してください", textRight=false)
                } else {
                    this.addChatgptHistory("予期せぬエラーが発生しました。再度お試しください。", textRight=false)
                }
            })
        },
        addChatgptHistory(text, textRight=false) {
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
                }, 200)
            }
            const handlePeriod = (...args) => {
                clearInterval(chatgptInterval)
                setTimeout(() => {
                    this.writeChatgpt(...args)
                }, 500)
            }
        },
        changePromptHeight() {
            this.$refs.prompt.style.height = this.promptDefaultHeight + 'px'
            const sh = this.$refs.prompt.scrollHeight
            this.$refs.prompt.style.height = sh + 10 + 'px'
        },
        placeholderByMode() {
            if (this.mode === 0) {
                return "Windowsパソコンを再起動するショートカットを教えてください"
            } else if (this.mode === 1) {
                return "御社の将来の目標は何ですか？"
            }
        }
    }
})
