<script setup lang="ts">
interface Props {
  label: string
  value: string
  valueColor?: string
}

withDefaults(defineProps<Props>(), {
  valueColor: 'text-white',
})
</script>

<template>
  <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
    <h3 class="text-gray-400 text-sm mb-2">{{ label }}</h3>
    <p class="text-xl md:text-3xl font-bold" :class="valueColor">
      {{ value }}
    </p>
    <slot />
  </div>
</template>
