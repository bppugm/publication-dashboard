<template>
  <div
    id="data-delete-modal"
    class="modal fade"
    tabindex="-1"
    aria-labelledby="dataDeleteModal"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header border-0 flex-column pb-0">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          />
          <div class="pt-2 w-100">
            <h5 class="modal-title text-primary fw-bold">Delete Data</h5>
            <hr />
          </div>
        </div>
        <div class="modal-body py-0">
          Are you sure to delete <b>{{ data.name }}</b> ?
        </div>
        <div class="modal-footer border-0">
          <div class="d-flex w-100">
            <button
              type="button"
              class="btn btn-soft-danger w-50 me-2"
              :disabled="isLoading"
              @click="deleteForm"
            >
              Yes
            </button>
            <button
              type="button"
              class="btn btn-soft-primary w-50 ms-2"
              data-bs-dismiss="modal"
            >
              No
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    selectedData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      data: {
        name: null,
      },
      errors: {},
      isLoading: false,
      modal: null,
    };
  },

  watch: {
    selectedData: {
      immediate: true,
      handler() {
        this.initModal();
      },
    },
  },

  mounted() {
    this.modal = new bootstrap.Modal(
      document.getElementById("data-delete-modal")
    );
  },

  methods: {
    initModal() {
      this.data = { ...this.selectedData };
    },

    async deleteForm() {
      this.isLoading = true;
      try {
        await axios.delete(`/data/${this.selectedData.id}`, this.data);
        this.modal.hide();
        this.$emit("deleted");
      } catch (error) {
        this.$toast.error("Delete failed", {
          position: "top-right",
          duration: 2000,
        });
      }
      this.isLoading = false;
    },
  },
};
</script>

<style scoped></style>
