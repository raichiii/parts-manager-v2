Vue.component('companies-index',{
    props: {
        getCompanies: String,
        routeCompanies: String
    },
    data(){
        return{
            companies: [],
            route: this.routeCompanies,
        }
    },
    mounted(){
        axios.get(
            this.getCompanies
        ).then(res => {
            this.companies = res.data
        }).catch(err => {
            console.log(err)
        })
    },
    methods: {
        getAll(results){
            this.companies = results
        }
    }
})
