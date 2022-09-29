<template>
  <span>{{ formatted }}</span>
</template>

<script>
import { parseISO, format } from "date-fns";

export default {
  props: {
    IsoDate: {
      type: String,
    },
    pattern: {
      type: String,
      default: "dd-MM-yyyy HH:mm",
    },
  },
  computed: {
    formatted() {
      var date = parseISO(this.IsoDate);
      return format(date, this.pattern);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
