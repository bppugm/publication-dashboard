<template>
    <div>
        <div class="d-flex justify-content-between mb-3">
            <h5 class="text-primary card-title mb-0">
                <b>DATA</b>
            </h5>
            <div class="text-dark"><b>Data List</b></div>
        </div>
        <div class="card border-0 shadow-sm p-3">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link active"
                        id="data-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#data"
                        type="button"
                        role="tab"
                        aria-controls="data"
                        aria-selected="true"
                    >
                        Data
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link"
                        id="category-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#category"
                        type="button"
                        role="tab"
                        aria-controls="category"
                        aria-selected="true"
                    >
                        Category
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div
                    id="data"
                    class="tab-pane fade show active"
                    role="tabpanel"
                    aria-labelledby="data-tab"
                >
                    <div class="d-flex my-3 justify-content-between">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="mdi mdi-filter-variant"></i> Filter
                        </button>
                        <div class="input-group w-50 w-md-75">
                            <input
                                id="search"
                                name="search"
                                type="text"
                                class="form-control"
                                placeholder="Search Data"
                                v-model="search"
                            />
                            <div
                                v-show="filter.keyword || search"
                                class="input-group-append"
                            >
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
                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#data-form-modal"
                            @click="addItem()"
                        >
                            <i class="mdi mdi-plus"></i> Add Data
                        </button>
                    </div>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Category</th>
                                <th scope="col">Description</th>
                                <th scope="col">Value</th>
                                <th scope="col">Assigned User</th>
                                <th scope="col">Last Update</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in filteredItems" :key="index">
                                <td>{{ item.name }}</td>
                                <td>-</td>
                                <td>{{ item.description }}</td>
                                <td>{{ item.value }}</td>
                                <td>-</td>
                                <td>{{ item.updated_at }}</td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#data-form-modal"
                                        @click="editItem(item)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#data-delete-modal"
                                        @click="deleteItem(item)"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div
                    id="category"
                    class="tab-pane fade"
                    role="tabpanel"
                    aria-labelledby="category-tab"
                >
                    Coming Soon
                </div>
            </div>
        </div>
        <data-form-modal :selectedData="selectedItem"></data-form-modal>
        <data-delete-modal :selectedData="selectedItem"></data-delete-modal>
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
            filter: {
                keyword: null,
            },
            selectedItem: {},
            isLoading: false,
            errors: {},
        };
    },
    methods: {
        resetSearch() {
            this.search = "";
            this.filter.keyword = null;
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
