import { defineStore } from "pinia";
import api from "../services/api"; // axios instance

export const useTicketStore = defineStore("ticket", {
  state: () => ({
    tickets: [],
    loading: false,
    error: null,
    categories: []
  }),

  actions: {
    showLoader() {
      this.loading = true;
    },
    hideLoader() {
      this.loading = false;
    },
    async fetchTickets() {
        this.loading = true;
        this.error = null;
        try {
            const res = await api.get("/tickets");
            this.tickets = res.data.listTickets.data; // <-- Grab the array only
        } catch (err) {
            this.error = "Failed to fetch tickets";
        } finally {
            this.loading = false;
        }
    },

    async addTicket(newTicket) {
      try {
        const res = await api.post("/tickets/create", newTicket);
        this.tickets.push(res.data);
      } catch (err) {
        console.error("Failed to add ticket:", err);
      }
    },

    async updateTicket(id, updatedTicket) {
      try {
        const res = await api.put(`/tickets/${id}`, updatedTicket);
        const index = this.tickets.findIndex(t => t.id === id);
        if (index !== -1) this.tickets[index] = res.data;
      } catch (err) {
        console.error("Failed to update ticket:", err);
      }
    },

    async deleteTicket(id) {
      try {
        await api.delete(`/tickets/${id}`);
        this.tickets = this.tickets.filter(t => t.id !== id);
      } catch (err) {
        console.error("Failed to delete ticket:", err);
      }
    },

    async fetchCategories() {
      try {
        const response = await api.get(`/categories`);
        this.categories = response.data.categories.map(cat => ({
          id: cat.id,
          name: cat.name
        }));
      } catch (err) {
        console.error("Failed to get categories:", err);
      }
    },

    async updateTicket(id, updatedTicket) {
      try {
        const res = await api.patch(`/tickets/update/${id}`, updatedTicket);
        const index = this.tickets.findIndex(t => t.id === id);
        if (index !== -1) this.tickets[index] = res.data;
      } catch (err) {
        console.error("Failed to update ticket:", err);
      }
    }
  },
});
