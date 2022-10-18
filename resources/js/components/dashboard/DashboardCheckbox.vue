<template>
    <div>
        <i
            class="mdi text-primary"
            :class="{
                'mdi-checkbox-blank-outline': !dashboard.order,
                'mdi-checkbox-marked': dashboard.order,
            }"
            style="font-size: 1.25rem; cursor: pointer"
            data-bs-toggle="modal"
            :data-bs-target="`#${modalId}`"
        ></i>
        <DashboardDeactivationModal
            v-if="dashboard.order"
            :modalId="modalId"
            :selectedData="dashboard"
        ></DashboardDeactivationModal>
        <DashboardActivationModal
            v-else
            :modalId="modalId"
            :selectedData="dashboard"
        ></DashboardActivationModal>
    </div>
</template>

<script>
import DashboardActivationModal from "./DashboardActivationModal.vue";
import DashboardDeactivationModal from "./DashboardDeactivationModal.vue";

export default {
    components: { DashboardActivationModal, DashboardDeactivationModal },
    props: {
        dashboard: {
            type: Object,
            required: true,
        },
    },
    computed: {
        modalId() {
            return `dashboard-activation-modal-${this.dashboard.id}`;
        },
    },
};
</script>

<style scoped></style>
