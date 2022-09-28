<template>
  <span class="badge text-bg-primary" :key="variable.text">
    {{ variable.text }}: {{ data.name }}</span>
</template>

<script>
export default {
  props: {
    variable: Object,
  },
  data() {
    return {
      data: {},
    };
  },

  mounted () {
    this.fetchData();
  },

  methods: {
    async fetchData() {
        try {
            let response = await axios.get(`/data/${this.variable.data_id}`)
            this.data = response.data
        } catch (error) {
            console.log(error)
        }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
