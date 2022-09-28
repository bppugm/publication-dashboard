<template>
  <div>
    <div>
      <div class="card-header text-primary bg-transparent p-0 pb-1">
        <h5>{{ data.name }}</h5>
      </div>
    </div>
    <div class="row gy-2 mt-2">
      <div class="col-6 col-lg-2">Current value</div>
      <div class="col-6 col-lg-9">: {{ data.value }}</div>
    </div>
    <div class="row gy-2">
      <div class="col-6 col-lg-2">Assigned user</div>
      <div class="col-6 col-lg-9">:</div>
    </div>
    <div class="row gy-2">
      <div class="col-6 col-lg-2">Category</div>
      <div class="col-6 col-lg-9">:</div>
    </div>
    <div class="row gy-2">
      <div class="col-6 col-lg-2">Description</div>
      <div class="col-6 col-lg-9">: {{ data.description }}</div>
    </div>
    <div class="row gy-2">
      <div class="col-6 col-lg-2">Notes</div>
      <div class="col-6 col-lg-9">: {{ data.notes }}</div>
    </div>
    <hr />
    <div class="d-flex justify-content-end">
      <button
        class="btn btn-outline-danger me-2"
        data-bs-toggle="modal"
        data-bs-target="#data-delete-modal"
      >
        Delete
      </button>
      <button
        class="btn btn-outline-primary"
        data-bs-toggle="modal"
        data-bs-target="#data-edit-modal"
      >
        Edit info
      </button>
    </div>

    <data-edit-modal
      :selected-data="data"
      @updated="data = $event"
    ></data-edit-modal>
    <data-delete-modal
      :selected-data="data"
      @deleted="onDeleted"
    ></data-delete-modal>
  </div>
</template>

<script>
export default {
  props: {
    initialData: {
      type: Object,
    },
  },

  data() {
    return {
      data: this.initialData,
    };
  },

  methods: {
    onDeleted() {
      this.$toast.success(`Data ${this.data.name} successfully deleted`, {
        position: "top",
      });

      window.location = "/data";
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
