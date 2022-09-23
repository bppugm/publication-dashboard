<template>
  <div>
    <div class="dropdown d-inline">
      <button
        class="btn btn-secondary dropdown-toggle"
        href="#"
        role="button"
        id="dropdownMenuLink"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        Add widget
      </button>

      <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <li>
          <button
            class="dropdown-item"
            data-bs-toggle="modal"
            data-bs-target="#addWidget"
            @click="initializeForm"
          >
            Numeric widget
          </button>
        </li>
        <li><button class="dropdown-item">Chart widget</button></li>
      </ul>
    </div>

    <!-- MODAL -->
    <div
      class="modal fade"
      id="addWidget"
      tabindex="-1"
      aria-labelledby="addWidgetLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addWidgetLabel">Add numeric widget</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="">
              <!-- RIBBON -->
              <div class="form-group">
                <label class="form-label"><b>Ribbon</b></label>
                <div class="row row-cols-md-2 g-2">
                  <div class="col-md-2">
                    <input
                      type="color"
                      class="form-control form-control-color"
                      v-model="form.ribbonColour"
                    />
                  </div>
                  <div class="col-md-10">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Enter ribbon title"
                      maxlength="12"
                      v-model="form.ribbonText"
                    />
                    <div class="d-flex justify-content-between">
                      <small class="form-text">Maximum 12 characters</small>
                      <small class="form-text"
                        >{{ form.ribbonText.length }} of 12</small
                      >
                    </div>
                  </div>
                </div>
              </div>
              <!-- END RIBBON -->

              <!-- TITLE -->
              <div class="form-group">
                <label class="form-label"
                  ><b>Title</b><sup class="text-danger">*</sup></label
                >
                <input
                  type="text"
                  class="form-control"
                  v-model="form.title"
                  maxlength="100"
                  required
                  placeholder="Enter widget title"
                />
                <div class="d-flex justify-content-between">
                  <small class="form-text">Maximum 100 characters</small>
                  <small class="form-text"
                    >{{ form.title.length }} of 100</small
                  >
                </div>
              </div>
              <!-- END TITLE -->

              <!-- UNIT -->
              <div class="form-group mt-3">
                <label class="form-label"><b>Unit</b></label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.unit"
                  maxlength="12"
                  placeholder="Enter widget unit"
                />
                <div class="d-flex justify-content-between">
                  <small class="form-text">Maximum 12 characters</small>
                  <small class="form-text">{{ form.unit.length }} of 12</small>
                </div>
              </div>
              <!-- END UNIT -->

              <!-- DESCRIPTION -->
              <div class="form-group mt-3">
                <label class="form-label"><b>Description</b></label>
                <textarea
                  v-model="form.description"
                  class="form-control"
                  cols="20"
                  rows="5"
                ></textarea>
              </div>
              <!-- END DESCRIPTION -->

              <hr />

              <!-- Values -->
              <div class="form-group mt-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <label class="form-label"><b>Values</b></label>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    @click="addValue()"
                  >
                    +
                  </button>
                </div>
              </div>

              <div
                class="mt-2"
                v-for="(value, index) in form.value"
                :key="index"
              >
                <div class="row row-cols-md-3 g-2">
                  <div class="col-md-3">
                    <select
                      class="form-control form-select"
                      v-model="form.value[index].type"
                    >
                      <option
                        v-for="type in valueTypes"
                        :value="type"
                        :key="type"
                      >
                        {{ type }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-8">
                    <dashboard-show-widget-data-selector
                      v-if="form.value[index].type == 'data'"
                      v-model="form.value[index].text"
                    ></dashboard-show-widget-data-selector>
                    <input
                      v-else
                      type="text"
                      class="form-control"
                      placeholder="Enter value"
                      maxlength="12"
                      v-model="form.value[index].text"
                    />
                  </div>
                  <div class="col-md-1">
                    <button
                      type="button"
                      @click="removeValue(index)"
                      class="btn btn-sm btn-outline-danger"
                    >
                      <i class="mdi mdi-delete-outline"></i>
                    </button>
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-6 d-grid">
                  <button
                    type="button"
                    class="btn btn-danger"
                    @click="initializeForm()"
                  >
                    Reset
                  </button>
                </div>
                <div class="col-md-6 d-grid">
                  <button
                    type="submit"
                    class="btn btn-primary"
                  >
                    Save
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- END DATA -->
  </div>
</template>

<script>
export default {
  props: {
    widgets: Array,
  },
  data() {
    return {
      form: {},
    };
  },
  computed: {
    initialWidget() {
      var i = this.widgets.length - 1;
      return {
        x: 0,
        y: 0,
        w: 3,
        h: 3,
        i: i,
        type: "value",
        title: "",
        description: "",
        value: [{...this.defaultValue}],
        unit: "",
        ribbonText: "",
        ribbonColour: "#404d68",
      };
    },
    valueTypes() {
      return ["data", "text", "expression"];
    },
    defaultValue() {
      return {
        type: this.valueTypes[0],
        text: "",
        variables: [],
      };
    },
  },
  mounted() {
    this.initializeForm();
  },
  methods: {
    initializeForm() {
      this.form = {...this.initialWidget};
    },
    addValue() {
      this.form.value.push({...this.defaultValue});
    },
    removeValue(index) {
      this.form.value.splice(index, 1);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
