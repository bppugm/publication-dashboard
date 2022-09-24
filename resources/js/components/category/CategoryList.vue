<template>
    <div>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="text-center">Colour</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Created Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in data" :key="index">
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
                    <td class="align-middle">
                        {{ formatDateTime(item.created_at) }}
                    </td>
                    <td class="align-middle">
                        <button
                            type="button"
                            class="btn btn-outline-primary my-1"
                            data-bs-toggle="modal"
                            data-bs-target="#category-edit-modal"
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
        <slot></slot>
        <category-edit-modal :selectedData="selectedItem"></category-edit-modal>
        <category-delete-modal :selectedData="selectedItem"></category-delete-modal>
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
