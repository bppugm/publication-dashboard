<template>
    <div
        id="category-edit-modal"
        class="modal fade"
        tabindex="-1"
        aria-labelledby="categoryEditModal"
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
                            Edit Category
                        </h5>
                        <hr />
                    </div>
                </div>
                <div class="modal-body py-0">
                    <!-- name -->
                    <div class="mb-3 required-field">
                        <label for="name" class="form-label fw-bold"
                            >Name</label
                        >
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
                                />
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
                                />
                                <div class="invalid-feedback">
                                    {{ getErrors("name") }}
                                </div>
                            </div>
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
                                max 500 characters.
                            </small>
                            <small
                                class="float-end"
                                :class="{
                                    'text-danger': countError,
                                    'text-muted': !countError,
                                }"
                            >
                                {{ count }} to 500
                            </small>
                        </div>
                    </div>
                </div>
                <!-- footer -->
                <div class="modal-footer border-0">
                    <div class="d-flex w-100">
                        <button
                            type="button"
                            class="btn btn-soft-danger w-50 me-2"
                            @click="resetForm"
                        >
                            Reset changes
                        </button>
                        <button
                            type="button"
                            class="btn btn-soft-primary w-50 ms-2"
                            v-show="isLoading == false"
                            @click="submitForm"
                        >
                            Save
                        </button>
                        <button
                            type="button"
                            v-show="isLoading"
                            class="btn btn-soft-primary disabled w-50 ms-2"
                        >
                            Saving...
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
            document.getElementById("category-edit-modal")
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
            this.count = this.form.description.length;
            this.countError = this.count > 500 ? true : false;
        },
        initModal() {
            this.form = { ...this.selectedData };
            this.errors = {};
        },
        resetForm() {
            this.form = { ...this.selectedData };
        },
        doSubmit() {
            this.submitForm();
        },
        async submitForm() {
            this.isLoading = true;
            try {
                let response = await axios.put(
                    `/category/${this.selectedData.id}`,
                    this.form
                );
                this.$emit("updated", response.data);

                this.$toast.success("Category updated", {
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
