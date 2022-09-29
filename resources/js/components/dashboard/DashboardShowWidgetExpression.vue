<template>
  <Mentionable
    :keys="['d']"
    :items="mappedItems"
    offset="6"
    insert-space
    @open="onOpen"
    @apply="onApply"
    filtering-disabled
    @search="fetchData($event)"
  >
    <textarea class="form-control" v-model="text" />

    <div>
      <dashboard-show-variable-badge
        class="me-2"
        v-for="variable in variables"
        :variable="variable"
        :key="variable.text"
      ></dashboard-show-variable-badge>
    </div>

    <template #no-result>
      <div class="dim">No result</div>
    </template>

    <template #item-d="{ item }">
      <div class="issue">
        <span class="number"> d{{ item.value }} </span>
        <span class="dim">
          {{ item.label }}
        </span>
      </div>
    </template>
  </Mentionable>
</template>

<script>
import { Mentionable } from "vue-mention";

export default {
  props: {
    variables: {
      type: Array,
    },
    value: String,
  },
  components: {
    Mentionable,
  },

  data() {
    return {
      text: "",
      items: [],
    };
  },
  computed: {
    mappedItems() {
      return this.items.map((item) => {
        return {
          value: item.id,
          label: item.name,
          original: item,
        };
      });
    },
  },

  watch: {
    text(newValue, oldValue) {
      this.$emit("input", this.text);
    },
  },

  mounted() {
    this.fetchData();
    this.text = this.value;
  },

  methods: {
    onOpen(key) {
      this.fetchData();
    },

    onApply(item, key, replacedWith) {
      this.$emit("selected", {
        text: replacedWith.trim(),
        data_id: item.value,
        original: item.original,
      });
    },

    async fetchData(search = null) {
      try {
        let response = await axios.get(`/data`, {
          params: {
            search: search,
          },
        });

        this.items = response.data.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>

<style scoped>
.issue {
  padding: 4px 6px;
  border-radius: 4px;
  cursor: pointer;
}

.mention-selected .issue {
  background: rgb(139, 212, 255);
}

.issue .number {
  font-family: monospace;
}

.dim {
  color: #666;
}

.preview {
  font-family: monospace;
  white-space: pre-wrap;
  margin-top: 12px;
  padding: 12px;
  background: #f8f8f8;
  border-radius: 6px;
}
</style>
