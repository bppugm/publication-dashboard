<template>
  <v-select
    placeholder="--Select Dashboard--"
    :options="dashboards"
    label="name"
    v-model="selected"
    @search="handleSearch"
    @input="handleSelected"
  >
  </v-select>
</template>

<script>
export default {
  props: {
    value: {
      type: Number,
    },
    initSelected: {
      type: Number,
    },
  },
  data() {
    return {
      dashboards: [],
      form: {
        search: null,
      },
      selected: null,
    };
  },
  watch: {
    initSelected: {
      immediate: true,
      handler() {
          this.initConnectToSelector();
      },
    },
  },
  mounted() {
    this.fetch();
  },
  methods: {
    initConnectToSelector() {
        this.selected = null;
        if (this.initSelected != null) {
            this.selected = this.dashboards.find(item => item.id == this.initSelected);
        }
    },
    async fetch() {
      try {
        let response = await axios.get(`/dashboard`, {
          params: this.form,
        });
        this.dashboards = response.data.data;
      } catch (error) {
        console.log(error);
      }
    },
    handleSearch(search) {
      this.form.search = search;
      this.fetch();
    },
    handleSelected(selected) {
      this.$emit("input", selected ? selected.id : null);
    },
  },
};
</script>

<style scoped>
</style>
