<template>
    <div>
        <div class="d-flex justify-content-between mb-3">
            <h5 class="text-primary card-title mb-0">
                <b>USER</b>
            </h5>
            <div class="text-dark"><b>User List</b></div>
        </div>
        <div class="card border-0 shadow-sm p-3">
            <div id="users" role="users-list" aria-labelledby="User-list">
                <div class="card-header text-primary bg-transparent">
                    <h5>User List</h5>
                </div>
                <!-- Heading Table -->
                <div class="d-flex my-3 justify-content-between">
                    <!-- Search bar -->
                    <div class="input-group w-50 w-md-75">
                        <input
                            id="search"
                            name="search"
                            type="text"
                            class="form-control"
                            placeholder="Search Data"
                            v-model="search"
                        />
                        <div v-show="search" class="input-group-append">
                            <button
                                type="button"
                                class="btn btn-search-append"
                                @click="resetSearch"
                            >
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                        <div class="input-group-append">
                            <button
                                type="button"
                                class="btn btn-input-append btn-outline-primary"
                            >
                                Search
                            </button>
                        </div>
                    </div>
                    <!-- Add User -->
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#user-form-modal"
                        @click="addItem()"
                    >
                        <i class="mdi mdi-plus"></i> Add User
                    </button>
                </div>
                <!-- Table -->
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Assigned Data</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in filteredItems" :key="index">
                            <td>{{ item.name }}</td>
                            <td>{{ item.email }}</td>
                            <td>{{ item.assigned_data }}</td>
                            <!-- Action -->
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#user-form-modal"
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
                    </tbody>
                </table>
            </div>
        </div>
        <user-form-modal :selectedData="selectedItem"></user-form-modal>
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
        resetSearch() {
            this.search = "";
        },
        addItem() {
            this.selectedItem = {};
        },
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
    computed: {
        filteredItems() {
            return this.data.filter((item) => {
                return (
                    item.name.toLowerCase().indexOf(this.search.toLowerCase()) >
                    -1
                );
            });
        },
    },
};
</script>
