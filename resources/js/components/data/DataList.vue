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
          <td class="align-middle">
            <data-category-button v-for="category in item.categories" :category="category" :key="category.id"></data-category-button>
          </td>
          <td class="align-middle">{{ item.value }}</td>
          <td class="align-middle">-</td>
          <td class="align-middle">
            <date-formatter :iso-date="item.updated_at"></date-formatter>
          </td>
          <td>
            <button
              type="button"
              class="btn btn-outline-primary my-1"
              data-bs-toggle="modal"
              data-bs-target="#data-edit-modal"
              @click="selectItem(item, index)"
            >
              Edit
            </button>
            <button
              type="button"
              class="btn btn-outline-danger my-1"
              data-bs-toggle="modal"
              data-bs-target="#data-delete-modal"
              @click="selectItem(item, index)"
            >
              Delete
            </button>
          </td>
        </tr>
        <!-- make table row if data is not found -->
        <tr v-if="data.length == 0">
          <td colspan="6" class="text-center">No data found</td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-between">
      <slot></slot>
    </div>
    <data-edit-modal
      :selectedData="selectedItem"
      @updated="handlerUpdate"
    ></data-edit-modal>
    <data-delete-modal
      :selectedData="selectedItem"
      @deleted="handleDeleted"
    ></data-delete-modal>
  </div>
</template>

<script>
import DateFormatter from "../DateFormatter.vue";
import DataCategoryButton from './DataCategoryButton.vue';
export default {
  components: { DateFormatter, DataCategoryButton },
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
      allData: [ ...this.data ],
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
    },
    selectItem(item, index) {
      this.selectedItem = item;
      this.selectedItem.index = index;
    },
    handlerUpdate(event) {
    //   this.allData[this.selectedItem.index] = event;
        this.selectedItem.name = event.name;
        this.selectedItem.description = event.description;
        this.selectedItem.notes = event.notes;
        this.selectedItem.value = event.value;
        this.selectedItem.categories = [ ...event.categories ];
    },
    handleDeleted(event) {
      this.allData.splice(this.selectedItem.index, 1);
      this.$toast.success(
        `Data ${this.selectedItem.name} successfully deleted`,
        {
          position: "top",
        }
      );
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
