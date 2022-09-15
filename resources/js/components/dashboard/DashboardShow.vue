<template>
  <div>
    <div class="mb-4 mt-3 card card-body">
      <div
        class="
          d-flex
          justify-content-between
          border-bottom border-3 border-warning
        "
      >
        <h3>UGM Publication achivements</h3>
        <h4>Jumat, 16 September 2022</h4>
      </div>
      <div class="bg-warning"></div>
    </div>

    <grid-layout
      :layout.sync="dashboard.widgets"
      :col-num="12"
      :row-height="30"
      :is-draggable="true"
      :is-resizable="true"
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
        <div class="my-auto" v-if="item.type == 'value'">
          <span class="h1" style="font-weight: 800">{{
            interpretValue(item.value)
          }}</span>
          <span>{{ item.unit }}</span>
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
      dashboard: {
        widgets: [],
        data: []
      },
      parser: new Parser(),
    };
  },
  mounted() {
    this.dashboard = this.initialDashboard;
  },
  methods: {
    interpretValue(value) {
      // check wheter the value is an equation or plain value
      if (Array.isArray(value)) {
        let values = [];
        value.forEach((item) => {
          if (item.type == "expression") {
            values.push(this.calculateValue(item));
          } else if (item.type == "data") {
            values.push(this.getDataValue(item.text));
          } else if (item.type == "text") {
            values.push(item.text);
          }
        });
        return values.join(" ");
      }

      return value;
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
