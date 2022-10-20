<template>
    <div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 83px">Display</th>
                        <th scope="col" style="width: 234px">Name</th>
                        <th scope="col" style="width: 464px">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(dashboard, index) in dashboards" :key="index">
                        <td class="text-center align-middle">
                            <DashboardCheckbox :dashboard="dashboard">
                            </DashboardCheckbox>
                        </td>
                        <td class="align-middle">
                            <a :href="`/dashboard/${dashboard.id}`">
                                {{ dashboard.name }}</a
                            >
                        </td>
                        <td class="align-middle">
                            {{ dashboard.description }}
                        </td>
                        <!-- Action -->
                        <td>
                            <div class="align-middle" v-if="canManage">
                                <button
                                    type="button"
                                    class="btn btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#dashboard-edit-modal"
                                    @click="editItem(dashboard, index)"
                                >
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#dashboard-delete-modal"
                                    @click="deleteItem(dashboard)"
                                >
                                    Delete
                                </button>
                                <a
                                    type="button"
                                    class="btn btn-outline-success"
                                    :href="`/dashboard/preview/${dashboard.id}`"
                                    role="button"
                                >
                                    Preview
                                </a>
                            </div>
                        </td>
                    </tr>
                    <!-- make table row if data is not found -->
                    <tr v-if="data.length == 0">
                        <td colspan="4" class="text-center">
                            No dashboard found
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-between">
                <slot></slot>
            </div>
            <dashboard-edit-modal
                @updated="handlerUpdate"
                :selectedData="selectedItem"
            ></dashboard-edit-modal>
            <dashboard-delete-modal
                :selectedData="selectedItem"
            ></dashboard-delete-modal>
        </div>
    </div>
</template>

<script>
import DashboardCheckbox from './DashboardCheckbox.vue';
export default {
    props: {
        data: {
            type: Array,
            default() {
                return [];
            },
        },
        canManage: Boolean,
    },
    data() {
        return {
            dashboards: { ...this.data },
            search: "",
            selectedItem: {},
            isLoading: false,
            errors: {},
        };
    },
    methods: {
        editItem(item, index) {
            this.selectedItem = item;
            this.selectedItem.index = index;
        },
        deleteItem(item) {
            this.selectedItem = item;
        },
        handlerUpdate(event) {
            this.dashboards[this.selectedItem.index] = event;
        },
        async doLogin() {
            this.isLoading = true;other
            try {
                let response = await axios.post("/login", this.filter);
                window.location = response.data.redirect;
            }
            catch (error) {
                console.log(error);
                this.errors = error.response.data.errors;
                this.filter.password = "";
            }
            finally {
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
    components: { DashboardCheckbox }
};
</script>
