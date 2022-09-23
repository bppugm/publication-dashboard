<template>
  <v-select
    placeholder="Search data"
    :options="data"
    label="name"
    v-model="selected"
    @search="handleSearch"
    @option:selected="handleSelected"
  ></v-select>
</template>

<script>
export default {
  props: {
    value: {
      type: Number,
    },
  },
  data() {
    return {
      data: [],
      form: {
        search: null,
      },
      selected: {},
    };
  },
  mounted() {
    this.fetch();
  },
  methods: {
    async fetch() {
      try {
        let response = await axios.get(`/data`, {
          params: this.form,
        });

        this.data = response.data.data;
      } catch (error) {}
    },
    debouncedFetch: _.debounce(function () {
      this.fetch();
    }, 350),
    handleSearch(search) {
      this.form.search = search;
      this.debouncedFetch();
    },
    handleSelected() {
      return this.$emit("input", this.selected.id);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
