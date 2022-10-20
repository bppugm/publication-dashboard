<template>
  <v-select
    placeholder="Search data"
    :options="data"
    label="name"
    v-model="selected"
    @search="handleSearch"
    @option:selected="handleSelected"
  >
    <template #option="option">
      {{ option.name }} &nbsp;
      <span class="text-secondary"> ({{ option.value }})</span>
    </template>
    <template v-if="selected.name" #selected-option="option">
      {{ option.name }} &nbsp;
      <span class="text-secondary"> ({{ option.value }})</span>
    </template>
    <template #search="{ attributes, events }">
      <input
        class="vs__search"
        :required="!selected.id"
        v-bind="attributes"
        v-on="events"
      />
    </template>
  </v-select>
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
    if (this.value) {
      // Fetch data details to populate the selected data
      this.fetchDataDetails();
    }

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
    async fetchDataDetails() {
      try {
        let response = await axios.get(`/data/${this.value}`);
        this.selected = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    debouncedFetch: _.debounce(function () {
      this.fetch();
    }, 350),
    handleSearch(search) {
      this.form.search = search;
      this.debouncedFetch();
    },
    handleSelected() {
      // Event input for v-model handler
      this.$emit("input", this.selected.id);

      // Event selected for passing data value
      this.$emit("selected", this.selected);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
