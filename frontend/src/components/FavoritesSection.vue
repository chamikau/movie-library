<template>
  <section id="favorites" class="section">
    <div class="container">
      <div class="heading-row">
        <div>
          <p class="eyebrow">
            COLLECT YOUR FAVORITES
          </p>
          <h2>
            What are you watching?
          </h2>
        </div>
        <span class="badge">
          {{ favorites.length }} saved
        </span>
      </div>
      <form
        class="search"
        @submit.prevent="searchShows"
      >
        <input
          v-model="searchQuery"
          type="search"
          placeholder="Search movies or TV shows..."
          aria-label="Search movies or TV shows"
          required
        />
        <button
          class="btn dark"
          type="submit"
          :disabled="searching"
        >
          {{ searching ? 'Searching...' : 'Search' }}
        </button>
      </form>
      <p
        class="status"
        aria-live="polite"
      >
        {{ searchStatus }}
      </p>
      <SearchResults
        :results="results"
        :favorites="favorites"
        @add="addFavorite"
        @remove="removeFavorite"
      />
    </div>
  </section>
</template>

<script setup>

import { ref, onMounted } from 'vue'
import SearchResults from './SearchResults.vue'

const props = defineProps({
  favorites: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits([
  'update:favorites'
])

const SEARCH_API = 'https://api.tvmaze.com/search/shows?q='
const DEFAULT_SHOWS_API = 'https://api.tvmaze.com/shows?page=0'
const DEFAULT_SHOWS_COUNT = 12

const searchQuery = ref('')
const searchStatus = ref('')
const searching = ref(false)
const results = ref([])

async function searchShows() {

  if (!searchQuery.value.trim()) {
    return
  }

  searching.value = true
  searchStatus.value = 'Searching TVMaze...'
  results.value = []

  try {

    const response = await fetch(
      SEARCH_API + encodeURIComponent(
        searchQuery.value.trim()
      )
    )

    if (!response.ok) {
      throw new Error()
    }

    results.value = await response.json()

    searchStatus.value = results.value.length
      ? `${results.value.length} result(s) found.`
      : 'No results found.'

  } catch {

    searchStatus.value =
      'Unable to connect to TVMaze.'

  } finally {

    searching.value = false

  }
}

function isFavorite(id) {

  return props.favorites.some(
    item => item.id === id
  )
}

function addFavorite(show) {

  if (isFavorite(show.id)) {

    searchStatus.value =
      `${show.name} is already in your favorites.`

    return
  }

  const updatedFavorites = [
    ...props.favorites,
    {
      id: show.id,
      name: show.name,
      type: show.type,
      summary: show.summary,
      image: show.image,
      url: show.url
    }
  ]

  emit(
    'update:favorites',
    updatedFavorites
  )

  localStorage.setItem(
    'movieFavorites',
    JSON.stringify(updatedFavorites)
  )

  searchStatus.value =
    `${show.name} added to favorites.`
}

function removeFavorite(id) {

  const updatedFavorites =
    props.favorites.filter(
      item => item.id !== id
    )

  emit(
    'update:favorites',
    updatedFavorites
  )

  localStorage.setItem(
    'movieFavorites',
    JSON.stringify(updatedFavorites)
  )

  searchStatus.value = 'Removed from favorites.'
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
function loadSavedFavorites() {
  const saved = localStorage.getItem('movieFavorites')
  if (!saved) {
    return
  }
  try {

    const parsed = JSON.parse(saved)

    if (Array.isArray(parsed) && parsed.length) {
      emit('update:favorites', parsed)
    }

  } catch {
  }
}
async function loadDefaultShows() {

  searching.value = true
  searchStatus.value = 'Loading shows...'

  try {
    const response = await fetch(DEFAULT_SHOWS_API)
    if (!response.ok) {
      throw new Error()
    }
    const shows = await response.json()
    results.value = shows
      .slice(0, DEFAULT_SHOWS_COUNT)
      .map(show => ({ show }))

    searchStatus.value = `Showing ${results.value.length} shows to get you started.`

  } catch {

    searchStatus.value = 'Unable to load shows right now.'

  } finally {

    searching.value = false

  }
}

onMounted(() => {
  loadSavedFavorites()
  loadDefaultShows()
})

</script>