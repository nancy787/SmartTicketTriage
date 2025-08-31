import { defineStore } from "pinia";

export const useUiStore = defineStore("ui", {
  state: () => ({
    loading: false, // global loader state
  }),
  actions: {
    showLoader() {
      this.loading = true;
    },
    hideLoader() {
      this.loading = false;
    },
  },
});
