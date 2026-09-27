<template>
  <div>
    <Header />

    <main>
      <HeroSection />

      <FavoritesSection
        :favorites="favorites"
        @update:favorites="favorites = $event"
      />

      <ContactSection />
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

import Header from './components/Header.vue'
import HeroSection from './components/HeroSection.vue'
import FavoritesSection from './components/FavoritesSection.vue'
import ContactSection from './components/ContactSection.vue'
import Footer from './components/Footer.vue'

const favorites = ref([])

onMounted(() => {
  favorites.value = JSON.parse(
    localStorage.getItem('movieFavorites') || '[]'
  )
})
</script>