import regx from '../../const/regularExpression'

Vue.component('individuals-edit', {
    props: {
        propsIndividual: Object,
    },
    data() {
        return {
            edit: {
                name: "",
                furigana: "",
                englishName: "",
                postalcode: "",
                address: "",
                phones: [],
                faxes: [],
                emails: [],
                urls: [],
            },
        }
    },
    created() {
        const individual = this.propsIndividual
        const edit = this.edit
        Object.keys(individual).forEach(function (key) {
            if (key in edit) {
                if (Array.isArray(edit[key])) {
                    if (individual[key].length > 0) {
                        individual[key].forEach(e => {
                            edit[key].push({
                                value: e
                            })
                        })
                    } else {
                        edit[key].push({
                            value: ''
                        })
                    }
                } else {
                    edit[key] = individual[key]
                }
            }else if(key == "english_name"){
                edit.englishName = individual[key]
            }
        })
    },
    computed: {
        validation() {
            const edit = this.edit
            return {
                name: (!!edit.name),
                furigana: (!edit.furigana || regx.hiragana.test(edit.furigana)),
                englishName: (!edit.englishName || regx.alphanumericName.test(edit.englishName)),
                postalcode: (!!edit.postalcode && regx.postalcode.test(edit.postalcode)),
                address: (!!edit.address),
                phones: edit.phones.every(e => {
                    return (!e.value || regx.phone.test(e.value))
                }),
                faxes: edit.faxes.every(e => {
                    return (!e.value || regx.phone.test(e.value))
                }),
                emails: edit.emails.every(e => {
                    return (!e.value || regx.email.test(e.value))
                }),
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
        addInput(property) {
            const additionalInput = {
                value: ''
            }
            this.edit[property].push(additionalInput)
        },
        deleteInput(property, id) {
            this.edit[property].splice(id, 1)
        },
    },
})
