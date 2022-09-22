<template>
  <div>
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
            <tr
                v-for="(item, index) in data"
                :key="index"
            >
                <td class="align-middle">{{ item.name }}</td>
                <td class="align-middle">-</td>
                <td class="align-middle">{{ item.description }}</td>
                <td class="align-middle">{{ item.value }}</td>
                <td class="align-middle">-</td>
                <td class="align-middle">{{ formatDateTime(item.updated_at) }}</td>
                <td>
                  <button
                      type="button"
                      class="btn btn-outline-primary my-1"
                      data-bs-toggle="modal"
                      data-bs-target="#data-edit-modal"
                      @click="editItem(item)"
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
        </tbody>
    </table>
    <data-edit-modal :selectedData="selectedItem"></data-edit-modal>
    <data-delete-modal :selectedData="selectedItem"></data-delete-modal>
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
          const date = new Date(dateTimeString)
          if (date instanceof Date && !isNaN(date)) {
            return format(new Date(dateTimeString), 'dd-MM-yyyy HH:mm')
          }
          return dateTimeString
        },
        resetSearch() {
            this.search = "";
        },
        editItem(item) {
            this.selectedItem = item;
        },
        deleteItem(item) {
            this.selectedItem = item;
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
