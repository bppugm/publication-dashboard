<template>
  <div>
    <!-- if no displayed dashboard -->
    <div v-if="items.length == 0">
      <div class="text-center">
        <div class="mb-3 mt-3">
          <h3 class="text-muted">No displayed dashboard found</h3>
        </div>
      </div>
    </div>
    <!-- draggable -->
    <draggable
      v-model="items"
      ghost-class="ghost"
      animation="200"
      @start="drag = true"
      @end="drag = false"
      @change="handlerChange"
    >
      <transition-group type="transition" name="items">
        <div v-for="(item, index) in items" :key="item.id">
          <div class="card shadow-sm my-1" style="min-height: 80px">
            <div class="card-body p-4 text-primary align-content-center row">
              <div class="col-10">
                <h6 class="me-2">
                  <b>{{ item.name }}</b>
                </h6>
                <h6 class="me-2 m-0">{{ item.description }}</h6>
              </div>
              <div class="text-primary align-self-center col-2">
                <div class="d-flex justify-content-end me-2">
                  <i
                    class="text-danger mdi mdi-delete fs-4"
                    style="font-size: 1.5rem; cursor: pointer"
                    data-bs-toggle="modal"
                    :data-bs-target="`#dashboard-activation-modal-${item.id}`"
                  ></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition-group>
    </draggable>
    <div v-if="items.length > 0" class="d-flex justify-content-end mt-2">
      <div class="d-flex w-50">
        <button
          type="button"
          class="btn btn-soft-danger w-50 me-2"
          @click="resetOrder"
          :disabled="!changedOrder"
        >
          Reset changes
        </button>
        <button
          type="button"
          class="btn btn-soft-primary w-50 ms-2"
          v-show="isLoading == false"
          @click="submitOrder"
          :disabled="!changedOrder"
        >
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
export default {
  components: {
    draggable,
  },
  props: {
    data: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  data() {
    return {
      items: [...this.data],
      drag: false,
      errors: {},
      changedOrder: null,
      isLoading: false,
    };
  },
  methods: {
    handlerChange() {
      // changeOrder will be filled with id and order from items
      this.changedOrder = this.items.map((item, index) => {
        return {
          id: item.id,
          order: index + 1,
        };
      });
    },
    resetOrder() {
      this.items = [...this.data];
      this.changedOrder = null;
    },
    async submitOrder() {
      this.loading = true;
      try {
        let response = await axios.put("/dashboard/order", this.changedOrder);
        this.$toast.success('Dashboard order has been updated', {
          position: "top",
          duration: 2000,
        });
      } catch (error) {
        this.errors = error.response.data.errors;
        this.$toast.error("Failed to update dashboard order", {
          position: "top",
          duration: 2000,
        });
      }
      this.loading = false;
    },
  },
};
</script>

<style lang="scss" scoped></style>
