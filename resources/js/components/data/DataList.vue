<template>
    <div>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 185px">Name</th>
                    <th scope="col" style="width: 364px">Category</th>
                    <th scope="col" style="width: 78px">Value</th>
                    <th scope="col" style="width: 126px">Assigned User</th>
                    <th scope="col" style="width: 219px">Last Update</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in allData" :key="index">
                    <td class="align-middle">
                        <a :href="`/data/${item.id}`"> {{ item.name }}</a>
                    </td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">{{ item.value }}</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">
                        {{ formatDateTime(item.updated_at) }}
                    </td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-outline-primary my-1"
                            data-bs-toggle="modal"
                            data-bs-target="#data-edit-modal"
                            @click="editItem(item, index)"
                        >
                            Edit
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-danger my-1"
                            data-bs-toggle="modal"
                            data-bs-target="#data-delete-modal"
                            @click="deleteItem(item)"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                <!-- make table row if data is not found -->
                    <tr v-if="data.length == 0">
                        <td colspan="6" class="text-center">
                            No data found
                        </td>
                    </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            <slot></slot>
        </div>
        <data-edit-modal :selectedData="selectedItem" @updated="handlerUpdate"></data-edit-modal>
        <data-delete-modal :selectedData="selectedItem"></data-delete-modal>
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
            allData: { ...this.data },
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
        formatDateTime(dateTimeString) {
            const date = new Date(dateTimeString);
            if (date instanceof Date && !isNaN(date)) {
                return format(new Date(dateTimeString), "dd-MM-yyyy HH:mm");
            }
            return dateTimeString;
        },
        resetSearch() {
            this.search = "";
        },
        editItem(item, index) {
            this.selectedItem = item;
            this.selectedItem.index = index;
        },
        deleteItem(item) {
            this.selectedItem = item;
        },
        handlerUpdate(event) {
            this.allData[this.selectedItem.index] = event;
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
