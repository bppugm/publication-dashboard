<template>
    <div>
        <div class="d-flex justify-content-between mb-3">
            <h5 class="text-primary card-title mb-0">
                <b>DATA</b>
            </h5>
            <div class="text-dark"><b>Category List</b></div>
        </div>
        <div class="card border-0 shadow-sm p-3">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="/data" class="text-decoration-none">
                        <button
                            class="nav-link disabled"
                            id="data-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#data"
                            type="button"
                            role="tab"
                            aria-controls="data"
                            aria-selected="true"
                        >
                            Data List
                        </button>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-tabs nav-link active border-0"
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
                    id="category"
                    class="tab-pane fade show active"
                    role="tabpanel"
                    aria-labelledby="category-tab"
                >
                    <div class="d-flex my-3 justify-content-between">
                        <div class="input-group me-3">
                            <input
                                id="search"
                                name="search"
                                type="text"
                                class="form-control"
                                placeholder="Search Category"
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
                        <div class="flex-shrink-0">
                          <button
                              type="button"
                              class="btn btn-primary"
                              data-bs-toggle="modal"
                              data-bs-target="#category-form-modal"
                              @click="addItem()"
                          >
                              <i class="mdi mdi-plus"></i> Add Category
                          </button>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Colour</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Created Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in filteredItems"
                                :key="index"
                            >
                                <td class="align-middle text-center">
                                  <i
                                      class="mdi mdi-square"
                                      :style="{
                                          color: item.colour,
                                          fontSize: '1.5rem',
                                      }"
                                  ></i>
                                </td>
                                <td class="align-middle">{{ item.name }}</td>
                                <td class="align-middle">{{ item.description }}</td>
                                <td class="align-middle">{{ formatDateTime(item.created_at) }}</td>
                                <td class="align-middle">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary my-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#category-form-modal"
                                        @click="editItem(item)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger my-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#category-delete-modal"
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
        </div>
        <category-form-modal :selectedData="selectedItem"></category-form-modal>
        <category-delete-modal
            :selectedData="selectedItem"
        ></category-delete-modal>
    </div>
</template>

<script>
import { format } from 'date-fns'
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
        formatDateTime(dateTimeString) {
          const date = new Date(dateTimeString)
          if (date instanceof Date && !isNaN(date)) {
            return format(new Date(dateTimeString), 'dd-MM-yyyy HH:mm')
          }
          return dateTimeString
        },
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
