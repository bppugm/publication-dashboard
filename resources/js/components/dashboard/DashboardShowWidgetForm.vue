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
            @click="addWidget()"
          >
            Numeric widget
          </button>
        </li>
        <li><button class="dropdown-item disabled">Chart widget</button></li>
      </ul>
    </div>

    <!-- MODAL -->
    <div
      class="modal fade"
      id="addWidget"
      tabindex="-1"
      aria-labelledby="addWidgetLabel"
      aria-hidden="true"
      data-bs-backdrop="static"
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
            <validation-observer v-slot="{ handleSubmit }">
              <form @submit.prevent="handleSubmit(submit)" v-if="form.type">
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
                          >{{ form.ribbonText ? form.ribbonText.length : 0 }} of
                          12</small
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
                    <small class="form-text"
                      >{{ form.unit ? form.unit.length : 0 }} of 12</small
                    >
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

                <!-- Values -->
                <div class="form-group mt-3 mb-3 bg-light p-1 text-center">
                  <b class="text-primary">Widget Values</b>
                </div>

                <div
                  class="mt-2 mb-1"
                  v-for="(value, index) in form.values"
                  :key="index"
                >
                  <label class="form-label"
                    ><b>Value {{ index + 1 }}</b></label
                  >
                  <div class="row row-cols-md-3 g-2">
                    <div class="col-md-4">
                      <select
                        class="form-control form-select"
                        v-model="form.values[index].type"
                      >
                        <option :value="null">Select type</option>
                        <option
                          v-for="type in valueTypes"
                          :value="type"
                          :key="`${index}.${type}`"
                        >
                          {{ type }}
                        </option>
                      </select>
                    </div>
                    <div class="col-md-7">
                      <input
                        v-if="form.values[index].type == null"
                        type="text"
                        class="form-control disabled"
                        disabled
                        placeholder="Select value type first"
                        maxlength="12"
                        required
                      />
                      <dashboard-show-widget-data-selector
                        v-if="form.values[index].type == 'data'"
                        v-model="form.values[index].text"
                        @selected="onSelectedData"
                      ></dashboard-show-widget-data-selector>
                      <dashboard-show-widget-expression
                        v-if="form.values[index].type == 'expression'"
                        v-model="form.values[index].text"
                        :variables="form.values[index].variables"
                        @selected="onSelectedExpression(index, $event)"
                      ></dashboard-show-widget-expression>
                      <input
                        v-if="form.values[index].type == 'text'"
                        type="text"
                        class="form-control"
                        placeholder="Enter value"
                        maxlength="12"
                        required
                        v-model="form.values[index].text"
                      />
                      <validation-provider
                        rules="nounbounds"
                        v-slot="{ errors }"
                      >
                        <input type="hidden" v-model="form.values[index]" />
                        <span class="text-danger">
                          {{ errors[0] }}
                        </span>
                      </validation-provider>
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

                <div class="d-grid mt-3">
                  <button
                    type="button"
                    class="btn btn-outline-primary"
                    @click="addValue()"
                  >
                    Add more value
                  </button>
                </div>

                <div class="row mt-3">
                  <div class="col-md-6 d-grid">
                    <button
                      type="button"
                      class="btn btn-danger"
                      @click="resetForm()"
                    >
                      Reset
                    </button>
                  </div>
                  <div class="col-md-6 d-grid">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
            </validation-observer>
          </div>
        </div>
      </div>
    </div>
    <!-- END DATA -->
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { Parser } from "expr-eval";

extend("nounbounds", (value) => {
  if (value.type != "expression") {
    return true;
  }

  try {
    var parser = new Parser();

    let subs = {};
    value.variables.forEach((item) => {
      subs[item.text] = item.data_id;
    });
    var vars = parser.parse(value.text).simplify(subs).variables();

    if (vars.length == 0) {
      return true;
    }

    return `Unbound variables found: ${vars.join(", ")}`;
  } catch (error) {
    return "Invalid expression";
  }
});

export default {
  components: { ValidationProvider, ValidationObserver },
  props: {
    widgets: Array,
    editedWidget: {
      type: Object,
      default: null,
    },
    editedWidgetIndex: Number,
  },
  data() {
    return {
      form: {},
      selectedData: [],
      modal: null,
      mode: "create",
    };
  },
  computed: {
    initialWidget() {
      var i = this.widgets ? this.widgets.length : 0;
      return {
        x: 0,
        y: 0,
        w: 4,
        h: 4,
        i: i,
        type: "numeric",
        title: "",
        description: "",
        values: [{ ...this.defaultValue }],
        unit: "",
        ribbonText: "",
        ribbonColour: "#404d68",
      };
    },
    valueTypes() {
      return ["text", "data", "expression"];
    },
    defaultValue() {
      return {
        type: null,
        text: null,
        variables: [],
      };
    },
  },
  watch: {
    editedWidget: {
      handler: function (newVal, oldVal) {
        // if a widget is being edited
        // populate the form with edited widget data
        //  and show the modal
        if (newVal.title) {
          this.populateFrom();
          this.mode = "edit";
        }
      },
      deep: true,
    },
  },
  mounted() {
    this.initializeForm();
    // Initialize bootstrap modal for manual action
    this.modal = new bootstrap.Modal(document.getElementById("addWidget"));
  },
  methods: {
    populateFrom() {
      // Reset form using edited widget value
      // JSON parse is used for deep copy
      this.form = JSON.parse(JSON.stringify(this.editedWidget));
    },
    addWidget() {
      this.mode = "create";
      this.initializeForm();
    },
    initializeForm() {
      this.form = { ...this.initialWidget };
      this.selectedData = [];
    },
    addValue() {
      this.form.values.push({ ...this.defaultValue });
    },
    resetForm() {
      if (this.mode == "edit") {
        this.populateFrom();
      } else if (this.mode == "create") {
        this.initializeForm();
      }
    },
    removeValue(index) {
      this.form.values.splice(index, 1);
    },
    onSelectedData(event) {
      this.selectedData.push(event);
    },
    onSelectedExpression(index, event) {
      this.form.values[index].variables.push({
        text: event.text,
        data_id: event.data_id,
      });
      this.onSelectedData(event.original);
    },
    submit() {
      this.$emit("submitted", {
        widget: this.form,
        data: this.selectedData,
        index: this.editedWidgetIndex,
      });
      this.initializeForm();
      this.modal.hide();
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
