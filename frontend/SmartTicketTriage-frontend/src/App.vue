<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { ref, watch } from 'vue'

// Theme state
const darkMode = ref(false)

// Watcher to toggle class on body
watch(darkMode, (val) => {
  if (val) {
    document.body.classList.add('dark')
  } else {
    document.body.classList.remove('dark')
  }
})
</script>

<template>
  <div id="app">
    <!-- Header -->
    <header class="app-header">
      <div class="app-header__wrapper">
        <nav class="app-header__nav">
          <RouterLink to="/dashboard" class="app-header__link">Dashboard</RouterLink>
          <RouterLink to="/tickets" class="app-header__link">Tickets</RouterLink>
        </nav>

        <!-- Theme Toggle -->
        <button class="theme-toggle" @click="darkMode = !darkMode">
          {{ darkMode ? '🌙 Dark' : '☀️ Light' }}
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="app-main">
      <RouterView />
    </main>

    <!-- Footer -->
    <footer class="app-footer">
      <p>© 2025 My App</p>
    </footer>
  </div>
</template>

<style>
/* RESET */
html, body, #app {
  height: 100%;
  margin: 0;
  padding: 0;
}

/* LAYOUT */
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.app-header {
  background: #333;
  color: #fff;
  padding: 1rem;
}

.app-header__wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.app-header__nav {
  display: flex;
  gap: 1rem;
}

.app-header__link {
  color: white;
  text-decoration: none;
}

.app-header__link.router-link-exact-active {
  font-weight: bold;
  text-decoration: underline;
}

.theme-toggle {
  background: transparent;
  border: 1px solid #fff;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  cursor: pointer;
  color: #fff;
  transition: background 0.2s;
}
.theme-toggle:hover {
  background: rgba(255, 255, 255, 0.1);
}

/* MAIN */
.app-main {
  flex: 1;
  padding: 1rem;
  background: #f4f6f8;
  transition: background 0.3s, color 0.3s;
}

/* FOOTER */
.app-footer {
  background: #222;
  color: #bbb;
  text-align: center;
  padding: 0.5rem;
  font-size: 14px;
}

/* DARK THEME */
body.dark {
  background: #121212;
  color: #eee;
}

body.dark .app-header {
  background: #1f1f1f;
}

body.dark .app-main {
  background: #181818;
  color: #eee;
}

body.dark .app-footer {
  background: #1f1f1f;
  color: #aaa;
}

body.dark .app-header__link {
  color: #ddd;
}

body.dark .theme-toggle {
  border-color: #ddd;
  color: #ddd;
}
</style>
