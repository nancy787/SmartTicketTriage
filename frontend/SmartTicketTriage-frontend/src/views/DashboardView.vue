<template>
  <div class="dashboard">
    <!-- Counters -->
    <div class="dashboard__counters">
      <div v-for="cat in ticketCounts" :key="cat.id" :class="['counter-card', `counter-card--${cat.name}`]">
        <h3 class="counter-card__title">{{ formatCategoryName(cat.name) }}</h3>
        <p class="counter-card__value">{{ cat.count }}</p>
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
import { onMounted, watch, computed } from 'vue'
import { storeToRefs } from 'pinia'
import Chart from 'chart.js/auto'
import { useTicketStore } from "../stores/ticketStore";

const ticketStore = useTicketStore()
const { categories, tickets } = storeToRefs(ticketStore)

let chartInstance = null

// Fetch data
onMounted(async () => {
  await ticketStore.fetchCategories();
  await ticketStore.fetchTickets();
})

// Compute ticket counts grouped by category
const ticketCounts = computed(() => {
  if (!categories.value.length || !tickets.value) return []

  return categories.value.map(cat => {
    const count = tickets.value.filter(
      t => t.category_id === cat.id
    ).length
    return { id: cat.id, name: cat.name, count }
  })
})

// Update Chart when ticketCounts changes
watch(ticketCounts, (counts) => {
  if (!counts.length) return
  const ctx = document.getElementById('chart')

  // Destroy old chart before re-creating
  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: counts.map(c => formatCategoryName(c.name)), // category names
      datasets: [{
        label: 'Tickets',
        data: counts.map(c => c.count), // ticket counts
        backgroundColor: [
          '#3498db', '#2ecc71', '#f39c12',
          '#9b59b6', '#e74c3c', '#1abc9c'
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  })
})

// Format category names (remove underscores + capitalize)
const formatCategoryName = (name) => {
  if (!name) return ""
  return name
    .split("_")
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" ")
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
  border-left: 5px solid #9b59b6;
}
.counter-card--feature_request {
  border-left: 5px solid #e74c3c;
}
.counter-card--other {
  border-left: 5px solid #1abc9c;
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
