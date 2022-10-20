<template>
  <div>
    <Carousel
      :perPage="1"
      :navigationClickTargetSize="8"
      :navigationEnabled="false"
      :autoplay="true"
      :autoplayHoverPause="false"
      :autoplayTimeout="5000"
      :loop="true"
      :paginationEnabled="true"
      paginationColor="#D9D9D9"
      paginationActiveColor="#495057"
    >
      <!-- foreach initial dashboards as dashboard use component dashboard-show with initial data dashboard -->
      <Slide v-for="(dashboard, index) in dashboards" :key="index" class="px-2">
        <div class="row">
          <div class="col-md-12">
            <div class="card my-3 shadow-sm">
              <div
                class="card-body border-warning d-flex justify-content-between align-items-center"
                style="border-bottom: 4px solid"
              >
                <div class="text-primary col">
                  <h4 class="m-0">
                    <!-- dashboard name in bold capitalize every word -->
                    <b>{{ Ucword(dashboard.name) }}</b>
                  </h4>
                </div>
                <div class="justify-content-end">
                  <i
                    class="mdi mdi-calendar text-center text-primary"
                    style="font-size: 1.25rem; font-style: normal;"
                  >
                    {{ dateToday() }}
                  </i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <DashboardShow
              :initial-dashboard="dashboard"
              :can-edit="false"
              :is-home="true"
            ></DashboardShow>
          </div>
        </div>
      </Slide>
    </Carousel>
  </div>
</template>

<script>
// import carousel and slide
import { Carousel, Slide } from "vue-carousel";
import DashboardShow from "./DashboardShow.vue";

export default {
  name: "DashboardCarousel",
  props: {
    dashboards: {
      type: Array,
      required: true,
    },
  },
  components: {
    Carousel,
    Slide,
    DashboardShow,
  },
  data() {
    return {};
  },
  methods: {
    Ucword(str) {
      return str.replace(
        /(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
        function (s) {
          return s.toUpperCase();
        }
      );
    },
    // date today month is in word
    dateToday() {
      let date = new Date();
      let month = date.toLocaleString("default", { month: "long" });
      return `${date.getDate()} ${month} ${date.getFullYear()}`;
    },
  },
};
</script>

<style lang="scss" scoped></style>
