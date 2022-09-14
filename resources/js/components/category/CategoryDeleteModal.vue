<template>
    <div
        id="category-delete-modal"
        class="modal fade"
        tabindex="-1"
        aria-labelledby="dataFormModal"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Category</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    Are you sure to delete {{ data.name }} ?
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-danger"
                        :disabled="isLoading"
                        @click="deleteForm"
                    >
                        Yes
                    </button>
                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-dismiss="modal"
                    >
                        No
                    </button>
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
                await axios.delete(`/category/${this.selectedData.id}`, this.data);
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
