<script setup lang="ts">
import type { CryptoListItem } from '~/types/crypto'
import { formatCurrency, formatBillions, formatPercentage, getPriceChangeArrow, getPriceChangeColor } from '~/utils/formatters'

interface Props {
  crypto: CryptoListItem
}

defineProps<Props>()
</script>

<template>
  <NuxtLink
    :to="`/crypto/${crypto.id}`"
    class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6 hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 cursor-pointer group"
  >
    <!-- Rank Badge -->
    <div class="flex items-start justify-between mb-4">
      <div class="flex items-center gap-3">
        <img :src="crypto.image" :alt="crypto.name" class="w-12 h-12 rounded-full" />
        <div>
          <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors">
            {{ crypto.name }}
          </h3>
          <p class="text-gray-400 uppercase text-sm">{{ crypto.symbol }}</p>
        </div>
      </div>
      <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">
        #{{ crypto.market_cap_rank }}
      </span>
    </div>

    <!-- Price -->
    <div class="mb-4">
      <p class="text-gray-400 text-sm mb-1">Current Price</p>
      <p class="text-2xl font-bold text-white">
        ${{ formatCurrency(crypto.current_price) }}
      </p>
    </div>

    <!-- Price Change -->
    <div class="mb-4">
      <p class="text-gray-400 text-sm mb-1">24h Change</p>
      <p 
        :class="getPriceChangeColor(crypto.price_change_percentage_24h)"
        class="text-lg font-semibold flex items-center gap-1"
      >
        <span>{{ getPriceChangeArrow(crypto.price_change_percentage_24h) }}</span>
        {{ formatPercentage(crypto.price_change_percentage_24h) }}%
      </p>
    </div>

    <!-- Market Cap -->
    <div>
      <p class="text-gray-400 text-sm mb-1">Market Cap</p>
      <p class="text-white font-semibold">
        ${{ formatBillions(crypto.market_cap) }}B
      </p>
    </div>

    <!-- View Details Hint -->
    <div class="mt-4 pt-4 border-t border-gray-700 text-center">
      <span class="text-blue-400 text-sm group-hover:text-blue-300">
        Click to view details →
      </span>
    </div>
  </NuxtLink>
</template>
