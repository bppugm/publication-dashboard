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
        <div id="data" class="tab-pane fade show active" role="tabpanel" aria-labelledby="data-tab">
          <div class="d-flex my-3">
            <div class="col-md-2">
              <button
                type="button"
                class="btn btn-outline-primary"
              >
                <i class="mdi mdi-filter-variant"></i> Filter
              </button>
            </div>
            <div class="col-md-8 px-0">
              <div class="input-group">
                <input
                  id="search"
                  name="search"
                  type="text"
                  class="form-control"
                  placeholder="Search Data"
                  v-model="form.search"
                />
                <div 
                  v-show="form.search"
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
            </div>
            <div class="col-md-2 text-end">
              <button
                type="button"
                class="btn btn-primary"
              >
                <i class="mdi mdi-plus"></i> Add Data
              </button>
            </div>
          </div>
          <table class="table table-light table-bordered">
            <thead class="thead-light">
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Category</th>
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
                <td>{{ item.name }}</td>
                <td>-</td>
                <td>{{ item.description }}</td>
                <td>{{ item.value }}</td>
                <td>-</td>
                <td>{{ item.updated_at }}</td>
                <td>

                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="category" class="tab-pane fade" role="tabpanel" aria-labelledby="category-tab">
          Coming Soon
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      default() { return []; },
    }
  },
  data() {
    return {
      form: {
        email: null,
        password: null,
        remember: true,
      },
      showPassword: false,
      isLoading: false,
      errors: {}
    }
  },
   methods: {
    resetSearch() {
      this.form.search = ''
    },
    async doLogin() {
      this.isLoading = true;

      try {
        let response = await axios.post("/login", this.form);
        window.location = response.data.redirect
      } catch (error) {
        console.log(error)
        this.errors = error.response.data.errors;
        this.form.password = '';
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
  }
}
</script>
