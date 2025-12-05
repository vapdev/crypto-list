<script setup lang="ts">
interface Props {
  title: string
  showBackButton?: boolean
  showSearchButton?: boolean
}

withDefaults(defineProps<Props>(), {
  showBackButton: false,
  showSearchButton: false,
})
</script>

<template>
  <header class="bg-gray-800/50 backdrop-blur-sm border-b border-gray-700 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <NuxtLink 
            v-if="showBackButton" 
            to="/" 
            class="text-blue-400 hover:text-blue-300 transition-colors"
          >
            ← Back to Home
          </NuxtLink>
          <h1 class="text-2xl md:text-3xl font-bold text-white">
            <span v-if="!showBackButton" class="mr-2">💰</span>
            <span :class="!showBackButton ? 'bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent' : ''">
              {{ title }}
            </span>
          </h1>
        </div>
        <NuxtLink 
          v-if="showSearchButton"
          to="/search" 
          class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center gap-2"
        >
          🔍 Search Cryptos
        </NuxtLink>
      </div>
    </div>
  </header>
</template>
