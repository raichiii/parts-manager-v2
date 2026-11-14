import regx from '../../const/regularExpression'

Vue.component('customers-edit', {
    props: {
        propsCustomer: Object,
    },
    data() {
        return {
            edit: {
                name: "",
                furigana: "",
                englishName: "",
                phones: [],
                faxes: [],
                emails: [],
            },
        }
    },
    created() {
        const customer = this.propsCustomer
        const edit = this.edit
        Object.keys(customer).forEach(function (key) {
            if (key in edit) {
                if (Array.isArray(edit[key])) {
                    if (customer[key].length > 0) {
                        customer[key].forEach(e => {
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
                    edit[key] = customer[key]
                }
            }else if(key == "english_name"){
                edit.englishName = customer[key]
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
                phones: edit.phones.every(e => {
                    return (!e.value || regx.phone.test(e.value))
                }),
                faxes: edit.faxes.every(e => {
                    return (!e.value || regx.phone.test(e.value))
                }),
                emails: edit.emails.every(e => {
                    return (!e.value || regx.email.test(e.value))
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
