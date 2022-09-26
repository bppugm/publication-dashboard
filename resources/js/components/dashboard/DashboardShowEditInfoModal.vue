<template>
  <div>
    <!-- Button trigger modal -->
    <button
      type="button"
      class="btn btn-outline-primary"
      data-bs-toggle="modal"
      data-bs-target="#editInfo"
    >
      Edit info
    </button>

    <!-- Modal -->
    <div
      class="modal fade"
      id="editInfo"
      tabindex="-1"
      aria-labelledby="editInfoLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editInfoLabel">Edit dashboard info</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form>
              <!-- NAME -->
              <div class="form-group">
                <label class="form-label"><b>Name</b></label>
                <input type="text" class="form-control" :class="{'is-invalid': form.errors.name}" v-model="form.name" />
                <div class="invalid-feedback">
                    {{ form.errors.name ? form.errors.name.join(" ") : '' }}
                </div>
              </div>
              <!-- END NAME -->

              <!-- DESCRIPTION -->
              <div class="form-group mt-4">
                <label class="form-label"><b>Description</b></label>
                <textarea
                  v-model="form.description"
                  class="form-control"
                  :class="{'is-invalid': form.errors.description}"
                  id=""
                  cols="30"
                  rows="5"
                ></textarea>
                <div class="invalid-feedback">
                    {{ form.errors.description ? form.errors.description.join(" ") : '' }}
                </div>
              </div>
              <!-- END DESCRIPTION -->

              <div class="row mt-4">
                <div class="col-md-6 d-grid">
                  <button
                    type="button"
                    class="btn btn-danger"
                    @click="resetForm()"
                  >
                    Reset
                  </button>
                </div>
                <div class="col-md-6 d-grid">
                  <button
                    type="button"
                    v-show="form.isLoading == false"
                    class="btn btn-primary"
                    @click="submit()"
                  >
                    Save
                  </button>
                  <button
                    type="button"
                    v-show="form.isLoading"
                    class="btn btn-primary disabled"
                  >
                    Saving...
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- END MODAL -->
  </div>
</template>

<script>
export default {
  props: {
    dashboard: {
      type: Object,
    },
  },
  data() {
    return {
      form: {
        name: null,
        description: null,
        isLoading: false,
        errors: {}
      },
      modal: null
    };
  },
  mounted() {
    this.resetForm();
    var modal = new bootstrap.Modal(document.getElementById("editInfo"));
    this.modal = modal
  },
  methods: {
    resetForm() {
      this.form.name = this.dashboard.name;
      this.form.description = this.dashboard.description;
      this.form.errors = {}
    },
    async submit() {
      this.form.isLoading = true;
      try {
        let response = await axios.put(
          `/dashboard/${this.dashboard.id}`,
          this.form
        );
        this.$emit("updated", response.data);

        this.$toast.success("Dashboard updated", {
          position: "top",
        });
        this.modal.toggle();
        this.resetForm();
      } catch (error) {
        console.log(error);
        this.form.errors = error.response.data.errors
      }

      this.form.isLoading = false;
    },
  },
};
</script>

<style lang="scss" scoped></style>
