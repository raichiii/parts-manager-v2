<template>
  <div>
    <div v-if="error" class="alert alert-warning" role="alert">{{ error }}</div>
    <div class="input-group">
      <input
        :id="name"
        :name="name"
        type="text"
        v-model="inputData"
        class="form-control py-2 border-right-0 border"
        autocomplete="off"
      />
      <span class="input-group-append">
        <div class="input-group-text bg-transparent">
          <i class="fa fa-search"></i>
        </div>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    propsUrl: String,
    propsData: String,
    propsName: String,
    propsQuery: Object,
  },
  model: {
    prop: "propsData",
    event: "input",
  },
  data() {
    return {
      results: [],
      url: this.propsUrl,
      name: this.propsName,
      error: null,
      query: null,
    };
  },
  created() {
    this.debouncedFetch = _.debounce(this.fetch, 400);
    this.query = {...{query: null}, ...this.propsQuery};
  },
  computed: {
    inputData: {
      get() {
        return this.propsData;
      },
      set(value) {
        if (this.propsData !== value) {
          this.debouncedFetch();
          this.$emit("input", value);
        }
      },
    },
  },
  methods: {
    fetch() {
      this.results = [];
      this.error = null;
      if (this.inputData.length >= 1) {
        this.query.query = this.inputData;
        console.log(this.query.query);
        axios
          .get(this.url, {
            params: this.query
          })
          .then((res) => {
            this.results = res.data;
            if (this.results) {
              this.$emit("suggest", this.results);
            }
          })
          .catch((error) => {
            console.log(error);
            this.error = error;
          });
      }
    },
  },
};
</script>
