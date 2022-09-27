<template>
    <div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 200px">Username</th>
                        <th scope="col" style="width: 200px">Email</th>
                        <th scope="col">Assigned Data</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in data" :key="index">
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ item.assigned_data }}</td>
                        <!-- Action -->
                        <td>
                            <button
                                type="button"
                                class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#user-edit-modal"
                                @click="editItem(item)"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="btn btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#user-delete-modal"
                                @click="deleteItem(item)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr v-if="data.length == 0">
                        <td colspan="4" class="text-center">No user found</td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-between">
                <slot></slot>
            </div>
        </div>
        <user-edit-modal :selectedData="selectedItem"></user-edit-modal>
        <user-delete-modal :selectedData="selectedItem"></user-delete-modal>
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
