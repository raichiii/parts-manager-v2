import regx from '../../const/regularExpression'

Vue.component('offices-create', {
    data() {
        return {
            create: {
                name: "",
                postalcode: "",
                address: "",
                phones: [{
                    value: ''
                }],
                faxes: [{
                    value: ''
                }],
                emails: [{
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
                postalcode: (!!create.postalcode && regx.postalcode.test(create.postalcode)),
                address: (!!create.address),
                phones: create.phones.every(e => {
                    return (!e.value || regx.phone.test(e.value))
                }),
                faxes: create.faxes.every(e => {
                    return (!e.value || regx.phone.test(e.value))
                }),
                emails: create.emails.every(e => {
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
            this.create[property].push(additionalInput)
        },
        deleteInput(property, id) {
            this.create[property].splice(id, 1)
        }
    },
})
