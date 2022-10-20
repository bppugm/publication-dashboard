<template>
    <div
        id="dashboard-delete-modal"
        class="modal fade"
        tabindex="-1"
        aria-labelledby="dashboardDeleteModal"
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
                            Delete Dashboard
                        </h5>
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
    methods: {
        initModal() {
            this.data = { ...this.selectedData };
        },

        async deleteForm() {
            this.isLoading = true;
            try {
                await axios.delete(`/dashboard/${this.selectedData.id}`, this.data);
                return location.reload();
            } catch (error) {
                this.errors = error.response.data.errors;
                alert("Failed to delete " + this.selectedData.name);
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
