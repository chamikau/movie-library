<template>
  <div v-if="results.length">
    <div class="results-grid">
      <article
        v-for="item in paginatedResults"
        :key="item.show.id"
        class="result-card"
      >
        <img
          :src="poster(item.show)"
          :alt="`${item.show.name} poster`"
        />
        <div class="result-body">
          <h3>{{ item.show.name }}</h3>
          <p>
            {{ summary(item.show.summary) }}
          </p>
          <button
            class="small-btn"
            :class="{ 'is-remove': isFavorite(item.show.id) }"
            @click="isFavorite(item.show.id) ? $emit('remove', item.show.id) : $emit('add', item.show)"
          >
            {{ isFavorite(item.show.id)
              ? 'Remove'
              : 'Add to favorites'
            }}
          </button>
        </div>
      </article>
    </div>
    <div v-if="totalPages > 1" class="pagination">
      <button
        class="page-btn"
        :disabled="currentPage === 1"
        @click="goToPage(currentPage - 1)"
        aria-label="Previous page"
      >
        ‹ Prev
      </button>
      <button
        v-for="page in totalPages"
        :key="page"
        class="page-btn"
        :class="{ active: page === currentPage }"
        @click="goToPage(page)"
      >
        {{ page }}
      </button>
      <button
        class="page-btn"
        :disabled="currentPage === totalPages"
        @click="goToPage(currentPage + 1)"
        aria-label="Next page"
      >
        Next ›
      </button>
    </div>
  </div>
</template>

<script setup>

import { ref, computed, watch } from 'vue'

const props = defineProps({
  results: {
    type: Array,
    default: () => []
  },

  favorites: {
    type: Array,
    default: () => []
  },

  itemsPerPage: {
    type: Number,
    default: 8
  }
})

defineEmits(['add', 'remove'])

const currentPage = ref(1)

const totalPages = computed(() =>
  Math.ceil(props.results.length / props.itemsPerPage)
)

const paginatedResults = computed(() => {
  const start = (currentPage.value - 1) * props.itemsPerPage
  return props.results.slice(start, start + props.itemsPerPage)
})

function goToPage(page) {
  if (page < 1 || page > totalPages.value) {
    return
  }
  currentPage.value = page
}
watch(() => props.results, () => {
  currentPage.value = 1
})

function isFavorite(id) {
  return props.favorites.some(item => item.id === id)
}

function stripHtml(value = '') {
  const div = document.createElement('div')
  div.innerHTML = value

  return (
    div.textContent ||
    div.innerText ||
    ''
  ).replace(/\s+/g, ' ').trim()
}

function summary(value) {
  const text = stripHtml(value)

  return text
    ? `${text.substring(0, 120)}${text.length > 120 ? '...' : ''}`
    : 'No description available.'
}

function poster(show) {
  return (
    show?.image?.medium ||
    show?.image?.original ||
    'https://via.placeholder.com/500x750?text=No+Image'
  )
}
</script>

<style scoped>
.small-btn {
  background-color: #16171a;
  color: #ffffff;
  border: none;
  border-radius: 6px;
  padding: 10px 14px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.15s ease;
}

.small-btn:hover {
  background-color: #2a2b2f;
}

.small-btn.is-remove {
  background-color: #b3261e;
}

.small-btn.is-remove:hover {
  background-color: #952019;
}

.pagination {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px;
  margin-top: 24px;
}

.page-btn {
  background-color: #1a1b1e;
  color: rgba(255, 255, 255, 0.75);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 6px;
  padding: 8px 12px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.15s ease, color 0.15s ease;
}

.page-btn:hover:not(:disabled) {
  background-color: #2a2b2f;
  color: #ffffff;
}

.page-btn:disabled {
  opacity: 0.4;
  cursor: default;
}

.page-btn.active {
  background-color: #4dd0e1;
  color: #0a0a0c;
  border-color: #4dd0e1;
}
</style>