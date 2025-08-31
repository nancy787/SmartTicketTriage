<!-- components/Loader.vue -->
<template>
  <div v-if="loading" class="loader-overlay">
    <div class="spinner"></div>
  </div>
</template>

<script setup>
import { useUiStore } from "@/stores/uiStore";
import { storeToRefs } from "pinia";

const uiStore = useUiStore();
const { loading } = storeToRefs(uiStore);
</script>

<style scoped>
.loader-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
.spinner {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-top: 4px solid #fff;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
