<template>
  <button @click="$emit('toggle')">
    Toggle to {{ theme === 'light' ? 'Dark' : 'Light' }} Mode
  </button>
</template>

<script setup>
defineProps({ theme: String })
</script>

<style scoped>
button {
  margin-bottom: 20px;
  padding: 8px 12px;
  cursor: pointer;
}
</style>
