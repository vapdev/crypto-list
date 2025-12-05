<script setup lang="ts">
import type { SearchResult } from '~/types/crypto'

interface Props {
  coin: SearchResult
}

defineProps<Props>()
</script>

<template>
  <NuxtLink
    :to="`/crypto/${coin.id}`"
    class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6 hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 cursor-pointer group flex items-center gap-4"
  >
    <img 
      v-if="coin.thumb"
      :src="coin.thumb" 
      :alt="coin.name"
      class="w-12 h-12 rounded-full"
    />
    <div class="flex-1">
      <h3 class="text-lg font-bold text-white group-hover:text-blue-400 transition-colors">
        {{ coin.name }}
      </h3>
      <p class="text-gray-400 uppercase text-sm">{{ coin.symbol }}</p>
    </div>
    <div class="flex items-center gap-3">
      <span v-if="coin.market_cap_rank" class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">
        #{{ coin.market_cap_rank }}
      </span>
      <span class="text-blue-400 text-sm">
        View details →
      </span>
    </div>
  </NuxtLink>
</template>
