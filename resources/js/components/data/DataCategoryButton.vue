<template>
  <span>
    <div
      class="btn btn-sm btn-outline-primary disabled me-2 rounded-4"
      :style="`color: ${category.colour}; border-color: ${category.colour}; opacity: 1;`"
    >
      {{ category.name }}
    </div>
  </span>
</template>

<script>
export default {
  props: {
    category: {
      type: Object,
    },
  },
};
</script>

<style>
</style>
