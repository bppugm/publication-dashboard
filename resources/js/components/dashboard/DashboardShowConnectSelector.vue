<template>
  <v-select
    class="style-chooser"
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
      type: Number|String,
    },
    initSelected: {
      type: Object,
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
        if (this.initSelected) {
          this.initDashboardSelector();
        }
      },
    },
  },
  mounted() {
    this.fetch();
  },
  methods: {
    initDashboardSelector() {
      this.selected = { ...this.initSelected };
    },
    async fetch() {
      try {
        let response = await axios.get(`/dashboard`, {
          params: this.form,
        });
        this.users = response.data.data;
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
