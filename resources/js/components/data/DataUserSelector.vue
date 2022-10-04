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
      type: Number,
    },
    initSelected: {
      type: Object,
    },
  },
  data() {
    return {
      users: [],
      form: {
        search: null,
      },
      selected: {
        id: null,
        name: "--Search User--",
      },
    };
  },
  watch: {
    initSelected: {
      immediate: true,
      handler() {
        if (this.initSelected) {
          this.initUserSelector();
        }
      },
    },
  },
  mounted() {
    this.fetch();
  },
  methods: {
    initUserSelector() {
      this.selected = { ...this.initSelected };
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
.style-chooser .vs__search::placeholder{
    color: #000;
}
</style>
