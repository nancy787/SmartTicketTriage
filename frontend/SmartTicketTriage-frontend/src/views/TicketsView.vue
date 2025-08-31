<template>
  <div class="tickets">
    <header class="tickets__header">
      <h1 class="tickets__title">🎫 Tickets</h1>
      <button class="tickets__new-btn" @click="showModal = true">+ New Ticket</button>
    </header>

    <!-- Filters -->
    <div class="tickets__filters">
      <select v-model="selectedCategory" class="tickets__filter-select">
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
      </select>

      <input 
        v-model="searchText" 
        placeholder="Search tickets..." 
        class="tickets__filter-input" 
      />
    </div>
    <!-- Tickets Table -->
    <div class="tickets__table-wrapper">
      <table class="tickets__table">
        <thead>
          <tr>
            <th>Subject</th>
            <th>Category</th>
            <th>Confidence</th>
            <th>Note</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ticket in filteredTickets" :key="ticket.id">
            <td>{{ ticket.subject }}</td>
           <td>
            <select v-model="ticket.category_id">
            <option 
              v-for="category in categories" 
              :key="category.id" 
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </select>
            </td>
            <td>
              <span class="tickets__confidence">
                {{ (ticket.ai_confidence * 100).toFixed(0) }}%
              </span>
            </td>
            <td>
              <span v-if="ticket.body" class="tickets__badge">📝</span>
            </td>
            <td>
              <button 
                class="tickets__action-btn" 
                @click="classify(ticket)" 
                :disabled="ticket.loading"
              >
                <span v-if="ticket.loading">⏳ Classifying...</span>
                <span v-else>Classify</span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="tickets__modal">
      <div class="tickets__modal-content">
        <h2 class="tickets__modal-title">➕ New Ticket</h2>
        <input 
          v-model="newTicket.subject" 
          placeholder="Subject" 
          class="tickets__modal-input" 
        />
        <textarea 
          v-model="newTicket.body" 
          placeholder="Body" 
          class="tickets__modal-textarea"
        ></textarea>

        <div class="tickets__modal-actions">
          <button class="tickets__modal-btn tickets__modal-btn--add" @click="addTicket">Add</button>
          <button class="tickets__modal-btn tickets__modal-btn--cancel" @click="showModal = false">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useTicketStore } from "../stores/ticketStore";

const ticketStore = useTicketStore()
const { tickets, loading, error } = storeToRefs(ticketStore) // <-- reactive refs
const { tickets, categories, loading, error } = storeToRefs(ticketStore) 

onMounted(async () => {
  await ticketStore.fetchTickets()     // load tickets
  await ticketStore.getCategories()    // load categories
})

const categories = ref([])
const selectedCategory = ref('')
const searchText = ref('')
const showModal = ref(false)
const newTicket = ref({
  subject: '',
  body: '',
  category: 'Support',
  confidence: 0.5
})

// Filters
const filteredTickets = computed(() => {
  return tickets.value.filter(ticket => {
    return (
      (selectedCategory.value === '' || ticket.category === selectedCategory.value) &&
      (searchText.value === '' || ticket.subject.toLowerCase().includes(searchText.value.toLowerCase()))
    )
  })
})

// Fake classifier
function classify(ticket) {
  ticket.loading = true
  setTimeout(() => {
    ticket.confidence = Math.random().toFixed(2)
    ticket.loading = false
  }, 1500)
}

// Add ticket
async function addTicket() {
  await ticketStore.addTicket(newTicket.value)
  showModal.value = false
  newTicket.value = { subject: '', body: '', category: 'Support', confidence: 0.5 }
}

</script>


<style>
/* Main Layout */
.tickets {
  padding: 20px;
  font-family: 'Segoe UI', Tahoma, sans-serif;
  background: #f8fafc;
  min-height: 100vh;
}

/* Header */
.tickets__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.tickets__title {
  font-size: 24px;
  font-weight: bold;
  color: #333;
}

.tickets__new-btn {
  background: #3498db;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  transition: background 0.2s;
}
.tickets__new-btn:hover {
  background: #2980b9;
}

/* Filters */
.tickets__filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 20px;
}
.tickets__filter-select,
.tickets__filter-input {
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 6px;
  flex: 1;
}

/* Table */
.tickets__table-wrapper {
  overflow-x: auto;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}
.tickets__table {
  width: 100%;
  border-collapse: collapse;
}
.tickets__table th, 
.tickets__table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}
.tickets__table th {
  background: #f1f5f9;
  font-weight: 600;
}

/* Badge & Confidence */
.tickets__badge {
  background: #ffeaa7;
  padding: 3px 6px;
  border-radius: 4px;
  font-size: 13px;
}
.tickets__confidence {
  font-weight: bold;
  color: #2ecc71;
}

/* Buttons */
.tickets__action-btn {
  background: #2ecc71;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
  color: white;
  font-size: 13px;
  cursor: pointer;
  transition: background 0.2s;
}
.tickets__action-btn:hover {
  background: #27ae60;
}
.tickets__action-btn:disabled {
  background: #bdc3c7;
  cursor: not-allowed;
}

/* Modal */
.tickets__modal {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  justify-content: center;
  align-items: center;
}
.tickets__modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  width: 90%;
  max-width: 400px;
}
.tickets__modal-title {
  margin-bottom: 1rem;
  font-size: 18px;
}
.tickets__modal-input,
.tickets__modal-textarea {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
}
.tickets__modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
.tickets__modal-btn {
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.tickets__modal-btn--add {
  background: #3498db;
  color: white;
}
.tickets__modal-btn--cancel {
  background: #ddd;
}

/* ==================== DARK THEME ==================== */
body.dark .tickets {
  background: #181818;
  color: #eee;
}

body.dark .tickets__title {
  color: #f1f1f1;
}

body.dark .tickets__table-wrapper {
  background: #242424;
  box-shadow: 0 2px 6px rgba(0,0,0,0.6);
}

body.dark .tickets__table th {
  background: #2c2c2c;
  color: #ddd;
}
body.dark .tickets__table td {
  border-bottom: 1px solid #333;
}

body.dark .tickets__filter-select,
body.dark .tickets__filter-input,
body.dark .tickets__modal-input,
body.dark .tickets__modal-textarea {
  background: #2a2a2a;
  border: 1px solid #444;
  color: #eee;
}

body.dark .tickets__modal-content {
  background: #242424;
  color: #eee;
}

body.dark .tickets__modal-btn--cancel {
  background: #444;
  color: #eee;
}

</style>
