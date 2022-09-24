<template>
  <div>
    <div class="card card-body p-4">
      <div class="d-flex justify-content-between">
        <h2 class="text-primary">{{ dashboard.name }}</h2>
        <div class="form-check form-switch">
          <label class="form-check-label" for="flexSwitchCheckChecked"
            >Edit mode</label
          >
          <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            id="flexSwitchCheckChecked"
            v-model="editMode"
          />
        </div>
      </div>
      <hr />
      <div class="row">
        <div class="col-md-9">
          <b>{{ dashboard.description }}</b>
        </div>
        <div class="col-md-3" v-show="editMode">
          <button class="btn btn-outline-danger">Delete</button>
          <dashboard-show-edit-info-modal
            class="d-inline"
            @updated="handleUpdatedInfo"
            :dashboard="dashboard"
          ></dashboard-show-edit-info-modal>
          <dashboard-show-widget-form
            class="d-inline"
            :widgets="dashboard.widgets"
            @submitted="handleWidgetSubmitted"
          ></dashboard-show-widget-form>
        </div>
      </div>
    </div>

    <grid-layout
      v-if="dashboard.widgets != null"
      :layout.sync="dashboard.widgets"
      :col-num="12"
      :row-height="30"
      :is-draggable="editMode"
      :is-resizable="editMode"
      :is-mirrored="false"
      :vertical-compact="true"
      :margin="[10, 10]"
      :use-css-transforms="true"
    >
      <grid-item
        class="card card-body pt-1"
        v-for="item in dashboard.widgets"
        :x="item.x"
        :y="item.y"
        :w="item.w"
        :h="item.h"
        :i="item.i"
        :key="item.i"
      >
        <div class="mb-3">
          <div class="ribbon bg-danger text-white">
            {{ item.i }} {{ item.ribbonText }}
          </div>
        </div>
        <h5>{{ item.title }}</h5>
        <div class="my-auto" v-if="item.type == 'chart'">
          <bar
            :chart-data="item.chartOptions.data"
            :chart-options="item.chartOptions.options"
          ></bar>
        </div>
        <div class="my-auto" v-if="item.type == 'numeric'">
          <span class="h1" style="font-weight: 800">{{
            interpretValue(item.values)
          }}</span>
          <span>{{ item.unit }}</span>
        </div>
        <div
          v-if="editMode"
          style="position: absolute; bottom: 10px; right: 20px"
          class="d-flex justify-content-end"
        >
          <span class="mx-1" style="cursor: grab"><i class="mdi mdi-clipboard"></i></span>
          <span class="mx-1" style="cursor: grab"><i class="mdi mdi-pencil-box-outline"></i></span>
          <span class="mx-1" style="cursor: grab" @click="removeWidget(index)"
            ><i class="mdi mdi-delete"></i
          ></span>
        </div>
      </grid-item>
    </grid-layout>
  </div>
</template>

<script>
import { Parser } from "expr-eval";
import VueGridLayout from "vue-grid-layout";
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);

export default {
  name: "DashboardShow",
  props: {
    initialDashboard: Object,
  },
  components: {
    GridLayout: VueGridLayout.GridLayout,
    GridItem: VueGridLayout.GridItem,
    Bar,
  },
  data() {
    return {
      dashboard: { ...this.initialDashboard },
      parser: new Parser(),
      editMode: true,
    };
  },
  watch: {
    editMode(newValue, oldValue) {
      if (newValue == false) {
        this.updateDashboard();
      }
    },
  },
  mounted() {
    this.dashboard = this.initialDashboard;
  },
  methods: {
    interpretValue(values) {
      // check wheter the value is an equation or plain value
      if (Array.isArray(values)) {
        let interpretedValues = [];
        values.forEach((item) => {
          if (item.type == "expression") {
            interpretedValues.push(this.calculateValue(item));
          } else if (item.type == "data") {
            interpretedValues.push(this.getDataValue(item.text));
          } else if (item.type == "text") {
            interpretedValues.push(item.text);
          }
        });
        return interpretedValues.join(" ");
      }

      return values;
    },
    getDataValue(id) {
      let data = this.dashboard.data.find(function (item) {
        return item.id == id;
      });

      if (data) {
        return data.value;
      }
    },
    calculateValue(value) {
      let subs = {};
      value.variables.forEach((item) => {
        subs[item.text] = this.getDataValue(item.data_id);
      });

      return this.parser.evaluate(value.text, subs);
    },
    handleUpdatedInfo(event) {
      this.dashboard.name = event.name;
      this.dashboard.description = event.description;
    },
    handleWidgetSubmitted(event) {
      if (this.dashboard.widgets == null) {
        this.dashboard.widgets = [];
      }

      this.dashboard.widgets.push(event.widget);

      event.data.forEach((item) => {
        this.dashboard.data.push(item);
      });
    },
    removeWidget(index) {
      this.dashboard.widgets.splice(index, 1);
    },
    async updateDashboard() {
      try {
        let response = await axios.put(
          `/dashboard/${this.dashboard.id}`,
          this.dashboard
        );
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>

<style>
.ribbon {
  --f: 5px; /* control the folded part*/
  --r: 15px; /* control the ribbon shape */
  --t: 10px; /* the top offset */

  position: absolute;
  inset: var(--t) calc(-1 * var(--f)) auto auto;
  padding: 0 10px var(--f) calc(10px + var(--r));
  clip-path: polygon(
    0 0,
    100% 0,
    100% calc(100% - var(--f)),
    calc(100% - var(--f)) 100%,
    calc(100% - var(--f)) calc(100% - var(--f)),
    0 calc(100% - var(--f)),
    var(--r) calc(50% - var(--f) / 2)
  );
  box-shadow: 0 calc(-1 * var(--f)) 0 inset #0005;
}
</style>
