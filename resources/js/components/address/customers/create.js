import regx from '../../const/regularExpression'

Vue.component('customers-create', {
    props: {
        officeApiUrl: String,
        propsOffice: Number,
    },
    data() {
        return {
            create: {
                name: "",
                furigana: "",
                englishName: "",
                offices: [],
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
            selectedCompany: document.getElementById('select-company').value,
            routeOffice: '',
        }
    },
    computed: {
        validation() {
            const create = this.create
            return {
                name: (!!create.name),
                furigana: (!create.furigana || regx.hiragana.test(create.furigana)),
                englishName: (!create.englishName || regx.alphanumericName.test(create.englishName)),
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
    created() {
        this.fetch()
        this.routeOffice = this.propsOffice
    },
    watch: {
        selectedCompany(after, before) {
            this.fetch()
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
        },
        fetch() {
            console.log(this.officeApiUrl)
            axios.get(this.officeApiUrl, {
                    params: {
                        company: this.selectedCompany
                    }
                })
                .then(res => {
                    this.create.offices = res.data
                })
                .catch(err => console.log(err))
        }
    },
})
