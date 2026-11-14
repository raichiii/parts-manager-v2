import regx from '../../const/regularExpression'

Vue.component('companies-edit', {
    props: {
        propsUrl: Array,
    },
    data() {
        return {
            selectedCompanyType: document.getElementById('type').value,
            edit: {
                name: document.getElementById('name').value,
                furigana: document.getElementById('furigana').value,
                englishName: document.getElementById('english-name').value,
                shortEnglishName: document.getElementById('short-english-name').value,
                urls: [],
            },
        }
    },
    created() {
        if (this.propsUrl.length > 0) {
            this.propsUrl.forEach(e => {
                this.edit.urls.push({
                    value: e
                })
            })
        } else {
            this.edit.urls.push({
                value: ''
            })
        }
    },
    computed: {
        validation() {
            const edit = this.edit
            return {
                name: (!!edit.name),
                furigana: (!edit.furigana || regx.hiragana.test(edit.furigana)),
                englishName: (!edit.englishName || regx.alphanumericSymbols.test(edit.englishName)),
                shortEnglishName: (!edit.shortEnglishName || regx.alphanumericSymbols.test(edit.shortEnglishName)),
                urls: edit.urls.every(e => {
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
            this.edit.urls.push(additionalUrl)
        },
        deleteUrlInput(id) {
            this.edit.urls.splice(id, 1)
        }
    },
})
