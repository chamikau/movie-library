<template>
  <section id="home" class="hero">
    <img :src="image" alt="" class="hero-image" />
  </section>
  <section class="intro">
    <div class="container">
      <h2 class="intro-title">{{ title  }}</h2>
      <p class="intro-text">{{ introText }}</p>
    </div>
  </section>
</template>

<script setup>
import cinemaHero from '../assets/images/cinema-hero.png'

defineProps({
  image: {
    type: String,
    default: cinemaHero
  },
  title: {
    type: String,
    default: 'Movie Library'
  },
  introText: {
    type: String,
    default: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
  }
})
</script>

<style scoped>
.hero {
  width: 100%;
  height: 400px;
  overflow: hidden;
}

.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.intro {
  background-color: #16171a;
  padding: 40px 0 56px;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 32px;
}

.intro-title {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 32px;
  font-weight: 800;
  letter-spacing: 0.01em;
  text-transform: uppercase;
  color: #ffffff;
  margin: 0 0 16px;
}

.intro-text {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 14px;
  line-height: 1.7;
  color: rgba(255, 255, 255, 0.65);
  max-width: 480px;
  margin: 0;
}

@media (max-width: 640px) {
  .hero {
    height: 240px;
  }

  .intro {
    padding: 28px 0 40px;
  }

  .container {
    padding: 0 20px;
  }

  .intro-title {
    font-size: 24px;
  }
}
</style>