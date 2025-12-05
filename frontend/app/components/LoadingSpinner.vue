<script setup lang="ts">
interface Props {
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
})

const sizeClasses = {
  sm: 'h-8 w-8',
  md: 'h-16 w-16',
  lg: 'h-24 w-24',
}
</script>

<template>
  <div class="flex justify-center items-center py-20">
    <div 
      class="animate-spin rounded-full border-t-2 border-b-2 border-blue-500"
      :class="sizeClasses[size]"
    />
  </div>
</template>
