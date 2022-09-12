<template>
  <div 
    id="data-form-modal" 
    class="modal fade" 
    tabindex="-1"
    aria-labelledby="dataFormModal" 
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ isEdit ? 'Edit' : 'Add'}} Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 required-field">
            <label for="name" class="form-label">Name</label>
            <input
              id="name"
              name="name"
              type="text"
              class="form-control"
              placeholder="Enter name"
              v-model="form.name"
              :class="{ 'is-invalid': hasErrors('name') }"
            />
            <div class="invalid-feedback">
              {{ getErrors("name") }}
            </div>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input
              id="description"
              name="description"
              type="text"
              class="form-control"
              placeholder="Enter description"
              v-model="form.description"
              :class="{ 'is-invalid': hasErrors('description') }"
            />
            <div class="invalid-feedback">
              {{ getErrors("description") }}
            </div>
          </div>
          <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <input
              id="notes"
              name="notes"
              type="text"
              class="form-control"
              placeholder="Enter notes"
              v-model="form.notes"
              :class="{ 'is-invalid': hasErrors('notes') }"
            />
            <div class="invalid-feedback">
              {{ getErrors("notes") }}
            </div>
          </div>
          <div class="mb-3">
            <label for="value" class="form-label">Value</label>
            <input
              id="value"
              name="value"
              type="text"
              class="form-control"
              placeholder="Enter value"
              v-model="form.value"
              :class="{ 'is-invalid': hasErrors('value') }"
            />
            <div class="invalid-feedback">
              {{ getErrors("value") }}
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" @click="resetForm">Reset</button>
          <button type="button" class="btn btn-primary" :disabled="isLoading" @click="submitForm">Save</button>
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
        name: null,
        description: null,
        notes: null,
        value: null,
      },
      errors: {},
      isLoading: false,
    };
  },
  computed: {
    isEdit() {
      if (Object.keys(this.selectedData).length === 0) {
        return false; 
      };
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
      this.submitForm()
    },
    async submitForm() {
      this.isLoading = true;
      try {
        if (this.isEdit) {
          await axios.put(`/data/${this.selectedData.id}`, this.form);
        } else {
          await axios.post(`/data`, this.form);
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

<style scoped>
</style>
