<script setup lang="ts">
interface Props {
  title?: string
  message: string
  showReturnLink?: boolean
}

withDefaults(defineProps<Props>(), {
  title: '❌ Error',
  showReturnLink: false,
})
</script>

<template>
  <div class="bg-red-900/20 border border-red-500 text-red-400 px-6 py-4 rounded-lg">
    <h2 class="text-xl font-bold mb-2">{{ title }}</h2>
    <p>{{ message }}</p>
    <NuxtLink 
      v-if="showReturnLink"
      to="/" 
      class="mt-4 inline-block text-blue-400 hover:text-blue-300"
    >
      Return to homepage →
    </NuxtLink>
  </div>
</template>
