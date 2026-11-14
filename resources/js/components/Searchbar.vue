<template>
    <div class="container">
        <div class="input-group mb-3">
            <input v-model="query" type="text" class="form-control" placeholder="入力してください">
            <div class="input-group-append">
                <button class="btn btn-primary" @click="fetch" type="button">
                    検索
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Searchbar",
        props: {
            fetchUrl: String,
        },
        data() {
            return {
                query: null,
                url: this.fetchUrl,
                results: [],
            }
        },
        created: function(){
            this.debouncedFetch = _.debounce(this.fetch, 400);
        },
        watch: {
            query(after,before){
                this.debouncedFetch()
            }
        },
        methods: {
            fetch(){
                axios.get(this.fetchUrl,{
                    params:{
                        query: this.query
                    }
                })
                .then(res=>{
                    this.results = res.data;
                    this.$emit('results',this.results);
                })
                .catch(err=>{
                    console.log(err);
                })
            }
        }
    }
</script>

<style scoped>

</style>
