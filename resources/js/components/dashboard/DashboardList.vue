<template>
    <div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 83px;">Display</th>
                        <th scope="col" style="width: 234px;">Name</th>
                        <th scope="col" style="width: 464px;">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in data" :key="index">
                        <!-- placeholder for display, waiting for feature-->
                        <td class="align-middle text-center" style="zoom: 1.5">
                            <input type="checkbox" />
                        </td>
                        <td class="align-middle">
                            <a :href="`/dashboard/${item.id}`"> {{ item.name }}</a>
                        </td>
                        <td class="align-middle">{{ item.description }}</td>
                        <!-- Action -->
                        <td class="align-middle">
                            <button
                                type="button"
                                class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#dashboard-edit-modal"
                                @click="editItem(item)"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="btn btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#dashboard-delete-modal"
                                @click="deleteItem(item)"
                            >
                                Delete
                            </button>
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
                :selectedData="selectedItem"
            ></dashboard-edit-modal>
            <dashboard-delete-modal
                :selectedData="selectedItem"
            ></dashboard-delete-modal>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        data: {
            type: Array,
            default() {
                return [];
            },
        },
    },
    data() {
        return {
            search: "",
            selectedItem: {},
            isLoading: false,
            errors: {},
        };
    },
    methods: {
        editItem(item) {
            this.selectedItem = item;
        },
        deleteItem(item) {
            this.selectedItem = item;
        },
        async doLogin() {
            this.isLoading = true;

            try {
                let response = await axios.post("/login", this.filter);
                window.location = response.data.redirect;
            } catch (error) {
                console.log(error);
                this.errors = error.response.data.errors;
                this.filter.password = "";
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
