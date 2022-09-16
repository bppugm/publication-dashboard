<template>
  <div
    id="category-form-modal"
    class="modal fade"
    tabindex="-1"
    aria-labelledby="CategoryFormModal"
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
            <h5 class="modal-title text-primary fw-bold">
              {{ isEdit ? 'Edit' : 'Add' }} Category
            </h5>
            <hr>
          </div>
        </div>
        <div class="modal-body py-0">
          <div class="mb-3 required-field">
            <label for="name" class="form-label fw-bold">Name</label>
            <div class="d-flex">
              <div>
                <input
                  id="colour"
                  v-model="form.colour"
                  type="color"
                  class="form-control form-control-color"
                  name="colour"
                  :class="{
                    'is-invalid': hasErrors('colour'),
                  }"
                >
                <div class="invalid-feedback">
                  {{ getErrors("colour") }}
                </div>
              </div>
              <div class="ms-2 w-100">
                <input
                  id="name"
                  v-model="form.name"
                  name="name"
                  type="text"
                  class="form-control"
                  placeholder="Enter Category Name"
                  :class="{ 'is-invalid': hasErrors('name') }"
                >
                <div class="invalid-feedback">
                  {{ getErrors("name") }}
                </div>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <input
              id="description"
              v-model="form.description"
              name="description"
              type="text"
              class="form-control"
              placeholder="Enter description"
              :class="{ 'is-invalid': hasErrors('description') }"
            >
            <div class="invalid-feedback">
              {{ getErrors("description") }}
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <div class="d-flex w-100">
            <button type="button" class="btn btn-danger w-50 me-2" @click="resetForm">Reset</button>
            <button type="button" class="btn btn-primary w-50 ms-2" :disabled="isLoading" @click="submitForm">Save</button>
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
      form: {
        colour: null,
        name: null,
        description: null,
      },
      errors: {},
      isLoading: false,
    };
  },
  computed: {
    isEdit() {
      if (Object.keys(this.selectedData).length === 0) {
        return false;
      }
      return true;
    },
  },
  watch: {
    selectedData: {
      immediate: true,
      handler() {
        this.initModal();
      },
    },
  },
  methods: {
    initModal() {
      if (this.isEdit) {
        this.form = { ...this.selectedData };
      } else {
        this.resetForm();
      }
    },
    resetForm() {
      Object.keys(this.form).forEach((key) => {
        this.form[key] = null;
      });
    },
    doSubmit() {
      this.submitForm();
    },
    async submitForm() {
      this.isLoading = true;
      try {
        if (this.isEdit) {
          await axios.put(
              `/category/${this.selectedData.id}`,
              this.form
          );
        } else {
          await axios.post(`/category`, this.form);
        }
        return location.reload();
      } catch (error) {
          this.errors = error.response.data.errors;
      } finally {
          this.isLoading = false;
      }
    },
    hasErrors(key) {
      if (this.errors[key]) {
        return true;
      }
      return false;
    },
    getErrors(key) {
      if (this.hasErrors(key)) {
        return this.errors[key].join(", ");
      }
      return "";
    },
  },
};
</script>

<style scoped></style>
