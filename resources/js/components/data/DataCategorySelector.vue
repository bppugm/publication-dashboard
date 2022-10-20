<template>
  <v-select
    placeholder="--Select Category--"
    :options="categories"
    label="name"
    v-model="selected"
    @search="handleSearch"
    @input="handleSelected"
    multiple
    :close-on-select="false"
  >
    <template #footer>
      <!-- ADD SLOT FOR HIDDEN INPUT -->
      <slot :selected="selected"></slot>
    </template>
  </v-select>
</template>

<script>
export default {
  props: {
    value: {
      type: Array,
    },
    initSelected: {
      type: Array,
    },
  },
  data() {
    return {
      categories: [],
      form: {
        search: null,
      },
      selected: [],
    };
  },
  watch: {
    initSelected: {
      immediate: true,
      handler() {
        if (this.initSelected) {
          this.initCategorySelector();
        }
      },
    },
  },
  mounted() {
    this.fetch();
  },
  methods: {
    initCategorySelector() {
      this.selected = [...this.initSelected];
    },
    async fetch() {
      try {
        let response = await axios.get(`/category`, {
          params: this.form,
        });

        this.categories = response.data.data;
      } catch (error) {
        console.log(error);
      }
    },
    debounceFetch: _.debounce(function () {
      this.fetch();
    }, 350),
    handleSearch(search) {
      this.form.search = search;
      this.debounceFetch();
    },
    handleSelected() {
      this.$emit(
        "input",
        this.selected.map((item) => item.id)
      );
    },
  },
};
</script>

<style lang='sccs' scoped>
</style>
