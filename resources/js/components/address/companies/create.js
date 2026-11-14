import regx from '../../const/regularExpression'

Vue.component('companies-create', {
    data() {
        return {
            selectedCompanyType: '',
            create: {
                name: "",
                furigana: "",
                englishName: "",
                shortFurigana: "",
                shortEnglishName: "",
                urls: [{
                    value: ''
                }],
            },
        }
    },
    computed: {
        validation() {
            const create = this.create
            return {
                name: (!!create.name),
                furigana: (!create.furigana || regx.hiragana.test(create.furigana)),
                englishName: (!create.englishName || regx.alphanumericSymbols.test(create.englishName)),
                shortFurigana: (!create.shortFurigana || regx.hiragana.test(create.shortFurigana)),
                shortEnglishName: (!create.shortEnglishName || regx.alphanumericSymbols.test(create.shortEnglishName)),
                urls: create.urls.every(e => {
                    return (!e.value || regx.url.test(e.value))
                }),
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
        addUrlInput() {
            const additionalUrl = {
                value: ''
            }
            this.create.urls.push(additionalUrl)
        },
        deleteUrlInput(id) {
            this.create.urls.splice(id, 1)
        },
    },
})
