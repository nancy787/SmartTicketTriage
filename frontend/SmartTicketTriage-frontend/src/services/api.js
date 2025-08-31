import axios from "axios";
import { useUiStore } from "@/stores/uiStore";

const api = axios.create({
  baseURL: "http://localhost:8000/api", // your Laravel API base
  headers: {
    "Content-Type": "application/json",
  },
});

// Show loader before request
api.interceptors.request.use((config) => {
  const uiStore = useUiStore();
  uiStore.showLoader();
  return config;
});

// Hide loader after response
api.interceptors.response.use(
  (response) => {
    const uiStore = useUiStore();
    uiStore.hideLoader();
    return response;
  },
  (error) => {
    const uiStore = useUiStore();
    uiStore.hideLoader();
    return Promise.reject(error);
  }
);

export default api;
