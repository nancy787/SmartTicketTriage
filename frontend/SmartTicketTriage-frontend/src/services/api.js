import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api", // your Laravel API base
  headers: {
    "Content-Type": "application/json",
  },
});

export default api;
