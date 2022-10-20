<template>
    <div
        :id="modalId"
        class="modal fade"
        tabindex="-1"
        aria-labelledby="dashboardActivationModal"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content text-start align-middle">
                <div class="modal-header border-0 flex-column pb-0">
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                    <div class="pt-2 w-100">
                        <h5 class="modal-title text-primary fw-bold text-start align-middle">
                            Activate Dashboard
                        </h5>
                        <hr />
                    </div>
                </div>
                <div class="modal-body py-0">
                    Are you sure to activate <b>{{ data.name }}</b> ?
                </div>
                <div class="modal-footer border-0">
                    <div class="d-flex w-100">
                        <button
                            type="button"
                            class="btn btn-soft-danger w-50 me-2"
                            data-bs-dismiss="modal"
                        >
                            No
                        </button>
                        <button
                            type="button"
                            class="btn btn-soft-primary w-50 ms-2"
                            :disabled="isLoading"
                            @click="activateForm"
                        >
                            Yes
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
        modalId: {
            type: String,
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

        async activateForm() {
            this.loading = true;
            try {
                let response = await axios.post("/dashboard/activation", {
                    id: this.data.id,
                });
                return location.reload();
            } catch (error) {
                this.errors = error.response.data.errors;
            }
            this.loading = false;
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
