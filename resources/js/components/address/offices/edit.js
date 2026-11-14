import regx from '../../const/regularExpression'

Vue.component('offices-edit', {
    props: {
        propsOffice: Object,
    },
    data() {
        return {
            edit: {
                name: "",
                postalcode: "",
                address: "",
                phones: [],
                faxes: [],
                emails: [],
            },
        }
    },
    created() {
        const office = this.propsOffice
        const edit = this.edit
        Object.keys(office).forEach(function (key) {
            if (key in edit) {
                if (Array.isArray(edit[key])) {
                    if (office[key].length > 0) {
                        office[key].forEach(e => {
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
                    edit[key] = office[key]
                }
            }
        })
    },
    computed: {
        validation() {
            const edit = this.edit
            return {
                name: (!!edit.name),
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
        }
    },
})
