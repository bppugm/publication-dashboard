<template>
  <v-select
    class="style-chooser"
    placeholder="--Select User--"
    :options="users"
    label="name"
    v-model="selected"
    @search="handleSearch"
    @input="handleSelected"
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
      type: Number | String,
    },
    initSelected: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      users: [],
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
        this.initUserSelector();
      },
    },
  },
  mounted() {
    this.fetch();
  },
  methods: {
    initUserSelector() {
      this.selected = this.initSelected == null ? null : { ...this.initSelected };
    },
    async fetch() {
      try {
        let response = await axios.get(`/user`, {
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
.style-chooser .vs__search::placeholder {
  color: #000;
}
</style>
