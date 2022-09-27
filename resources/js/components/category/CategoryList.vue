<template>
    <div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" style="width: 80px;">Colour</th>
                        <th scope="col" style="width: 185px;">Name</th>
                        <th scope="col" style="width: 364px;">Description</th>
                        <th scope="col" style="width: 219px;">Created Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(category, index) in categories" :key="index">
                        <td class="align-middle text-center">
                            <i
                                class="mdi mdi-square"
                                :style="{
                                    color: category.colour,
                                    fontSize: '1.5rem',
                                }"
                            ></i>
                        </td>
                        <td class="align-middle">{{ category.name }}</td>
                        <td class="align-middle">{{ category.description }}</td>
                        <td class="align-middle">
                            {{ formatDateTime(category.created_at) }}
                        </td>
                        <td class="align-middle">
                            <button
                                type="button"
                                class="btn btn-outline-primary my-1"
                                data-bs-toggle="modal"
                                data-bs-target="#category-edit-modal"
                                @click="editItem(category, index)"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="btn btn-outline-danger my-1"
                                data-bs-toggle="modal"
                                data-bs-target="#category-delete-modal"
                                @click="deleteItem(category)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    <!-- make table row if data is not found -->
                    <tr v-if="data.length == 0">
                        <td colspan="5" class="text-center">
                            No category found
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-between">
                <slot></slot>
            </div>
            <category-edit-modal :selectedData="selectedItem" @updated="handlerUpdate"></category-edit-modal>
            <category-delete-modal
                :selectedData="selectedItem"
            ></category-delete-modal>
        </div>
    </div>
</template>

<script>
import { format } from "date-fns";
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
            categories: { ...this.data},
            search: "",
            selectedItem: {},
            isLoading: false,
            errors: {},
        };
    },
    methods: {
        formatDateTime(dateTimeString) {
            const date = new Date(dateTimeString);
            if (date instanceof Date && !isNaN(date)) {
                return format(new Date(dateTimeString), "dd-MM-yyyy HH:mm");
            }
            return dateTimeString;
        },
        editItem(item, index) {
            this.selectedItem = item;
            this.selectedItem.index = index;
        },
        deleteItem(item) {
            this.selectedItem = item;
        },
        handlerUpdate(event) {
            this.categories[this.selectedItem.index] = event;
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
