<template>
  <div class="card">
    <h3>{{ subject }}</h3>
    <p>{{ count }}</p>
  </div>
</template>

<script setup>
defineProps({
  subject: String,
  count: Number
})
</script>

<style scoped>
.card {
  background: #f2f2f2;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  flex: 1;
}
</style>
