<template>
    <div
        id="data-edit-modal"
        class="modal fade"
        tabindex="-1"
        aria-labelledby="dataEditModal"
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
                            Edit Data
                        </h5>
                        <hr />
                    </div>
                </div>
                <div class="modal-body py-0">
                    <!-- Name -->
                    <div class="mb-3 required-field">
                        <label for="name" class="form-label fw-bold"
                            >Name</label
                        >
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
                    <!-- Category -->
                    <div class="mb-3 required-field">
                        <label for="name" class="form-label fw-bold"
                            >Category</label>
                        <data-category-selector
                            v-model="form.categories"
                            :initSelected="selectedData.categories"
                            :class="{ 'is-invalid': hasErrors('categories') }"
                        ></data-category-selector>
                    </div>
                    <!-- Value -->
                    <div class="mb-3">
                        <label for="value" class="form-label fw-bold"
                            >Value</label
                        >
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
                    <!-- Assign to User -->
                    <div class="mb-3 required-field">
                        <label for="user" class="form-label fw-bold"
                            >Assign to User</label>
                        <data-user-selector
                            v-model="form.user_id"
                            :initSelected="selectedData.user"
                            :class="{ 'is-invalid': hasErrors('user') }"
                        ></data-user-selector>
                    </div>
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold"
                            >Description</label
                        >
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
                    <!-- Note -->
                    <div class="mb-3">
                        <label for="Notes" class="form-label fw-bold"
                            >Note</label
                        >
                        <textarea
                            id="notes"
                            v-model="form.notes"
                            v-on:keyup="counting"
                            name="notes"
                            rows="5"
                            class="form-control"
                            placeholder="Enter Note"
                            :class="{ 'is-invalid': hasErrors('notes') }"
                        ></textarea>
                        <div class="invalid-feedback">
                            {{ getErrors("note") }}
                        </div>
                        <div>
                            <small class="text-muted">
                                max 500 characters.
                            </small>
                            <small
                                class="float-end"
                                :class="{'text-danger': countError, 'text-muted': !countError}">
                                {{ count }} to 500
                            </small>
                        </div>
                    </div>
                </div>
                <!-- Button -->
                <div class="modal-footer border-0">
                    <div class="d-flex w-100">
                        <button
                            type="button"
                            class="btn btn-danger w-50 me-2"
                            @click="resetForm"
                        >
                            Reset
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary w-50 ms-2"
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
                categories: [],
                user_id: null,
            },
            errors: {},
            isLoading: false,
            count: 0,
            countError: false,
            modal: null,
        };
    },
    mounted() {
        this.resetForm();
        var modal = new bootstrap.Modal(
            document.getElementById("data-edit-modal")
        );
        this.modal = modal;
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
        counting() {
            this.count = this.form.notes.length;
            this.countError = this.count > 500 ? true : false;
        },
        initModal() {
            this.form = { ...this.selectedData };
            if (this.form.categories){
                this.form.categories = this.form.categories.map(item => item.id);
            }
            this.errors = {};
        },
        resetForm() {
            this.initModal();
        },
        doSubmit() {
            this.submitForm();
        },
        async submitForm() {
            this.isLoading = true;
            try {
                let response = await axios.put(`/data/${this.selectedData.id}`, this.form);
                this.$emit("updated", response.data);

                this.$toast.success("Data updated", {
                    position: "top",
                    duration: 2000,
                });
                this.modal.toggle();
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
