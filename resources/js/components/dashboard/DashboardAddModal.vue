<template>
    <div
        id="dashboard-add-modal"
        class="modal fade"
        tabindex="-1"
        aria-labelledby="dashboardAddModal"
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
                    ></button>
                    <div class="pt-2 w-100">
                        <h5 class="modal-title text-primary fw-bold">
                            Add Dashboard
                        </h5>
                        <hr />
                    </div>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="mb-3 required-field">
                        <label for="name" class="form-label fw-bold"
                            >Nama</label
                        >
                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="form-control"
                            placeholder="Enter Name"
                            v-model="form.name"
                            :class="{ 'is-invalid': hasErrors('name') }"
                        />
                        <div class="invalid-feedback">
                            {{ getErrors("name") }}
                        </div>
                    </div>
                    <!-- description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold"
                            >Description</label
                        >
                        <textarea
                            id="description"
                            v-model="form.description"
                            v-on:keyup="counting"
                            name="description"
                            rows="5"
                            class="form-control"
                            placeholder="Enter description"
                            :class="{ 'is-invalid': hasErrors('description') }"
                        ></textarea>
                        <div class="invalid-feedback">
                            {{ getErrors("description") }}
                        </div>
                        <div>
                            <small class="text-muted">
                                max 300 characters.
                            </small>
                            <small
                                class="float-end"
                                :class="{
                                    'text-danger': countError,
                                    'text-muted': !countError,
                                }"
                            >
                                {{ count }} to 300
                            </small>
                        </div>
                    </div>
                </div>
                <!-- Submit  -->
                <div class="modal-footer border-0">
                    <div class="d-flex w-100">
                        <button
                            type="button"
                            class="btn btn-soft-danger w-50 me-2"
                            @click="resetForm"
                        >
                            Reset
                        </button>
                        <button
                            type="button"
                            class="btn btn-soft-primary w-50 ms-2"
                            :disabled="isLoading"
                            @click="submitForm"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: null,
                description: null,
            },
            errors: {},
            isLoading: false,
            count: 0,
            countError: false,
        };
    },
    methods: {
        counting() {
            this.count = this.form.description.length;
            if (this.count > 300) {
                this.countError = true;
            } else {
                this.countError = false;
            }
        },
        initModal() {
            this.resetForm();
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
                await axios.post("/dashboard", this.form);
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
