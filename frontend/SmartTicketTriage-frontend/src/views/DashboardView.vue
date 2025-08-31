<template>
  <div class="dashboard">
    <!-- Counters -->
    <div class="dashboard__counters">
      <div v-for="category in categories" :class="['counter-card', `counter-card--${category.name}`]">
        <h3 class="counter-card__title">{{ formatCategoryName(category.name) }}</h3>
        <p class="counter-card__value">12</p>
      </div>
    </div>

    <!-- Chart -->
    <div class="dashboard__chart">
      <h3 class="dashboard__chart-title">Ticket Status Overview</h3>
      <canvas id="chart"></canvas>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import Chart from 'chart.js/auto'
import { useTicketStore } from "../stores/ticketStore";

const ticketStore = useTicketStore()
const { categories } = storeToRefs(ticketStore);
const { tickets, loading, error } = storeToRefs(ticketStore)

onMounted(async () => {
  await ticketStore.fetchCategories();
  await ticketStore.fetchTickets();
})


onMounted(() => {
  const ctx = document.getElementById('chart')
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Open', 'Closed', 'Pending'],
      datasets: [{
        label: 'Tickets',
        data: [12, 15, 7, 12, 15, 7],
        backgroundColor: ['#3498db', '#2ecc71', '#f39c12']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      }
    }
  })
})
const formatCategoryName = (name) => {
  if (!name) return "";
  return name
    .split("_")                   // split on underscore
    .map(word => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize
    .join(" ");                   // join with space
}

</script>

<style>
/* Layout */
.dashboard {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 2rem;
  background: #f8f9fa;
  min-height: 100vh;
  transition: background 0.3s, color 0.3s;
}

/* Counter Section */
.dashboard__counters {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.counter-card {
  background: #fff;
  padding: 1rem;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.2s, background 0.3s, color 0.3s;
}
.counter-card:hover {
  transform: translateY(-3px);
}
.counter-card__title {
  font-size: 14px;
  color: #666;
  margin-bottom: 0.5rem;
}
.counter-card__value {
  font-size: 24px;
  font-weight: bold;
}

.counter-card--billing {
  border-left: 5px solid #3498db;
}
.counter-card--technical {
  border-left: 5px solid #2ecc71;
}
.counter-card--account {
  border-left: 5px solid #f39c12;
}
.counter-card--bug {
  border-left: 5px solid #2112f3;
}
.counter-card--feature_request {
  border-left: 5px solid #e012f3;
}
.counter-card--other {
  border-left: 5px solid #f39c12;
}

/* Chart Section */
.dashboard__chart {
  background: #fff;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  transition: background 0.3s, color 0.3s;
}
.dashboard__chart-title {
  margin-bottom: 1rem;
  font-size: 16px;
  font-weight: bold;
}

/* ==================== DARK THEME ==================== */
body.dark .dashboard {
  background: #181818;
  color: #eee;
}

body.dark .counter-card {
  background: #242424;
  color: #eee;
  box-shadow: 0 2px 6px rgba(0,0,0,0.6);
}
body.dark .counter-card__title {
  color: #aaa;
}

body.dark .dashboard__chart {
  background: #242424;
  color: #eee;
  box-shadow: 0 2px 6px rgba(0,0,0,0.6);
}
</style>
