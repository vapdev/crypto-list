<script setup lang="ts">
import { formatCurrency, formatBillions, formatPercentage, getPriceChangeArrow, getPriceChangeColor } from '~/utils/formatters'

// Fetch top cryptocurrencies using composable
const { getTopCryptos } = useCryptoApi()
const { data: cryptos, pending, error } = await getTopCryptos()

// SEO meta tags
useHead({
  title: 'Top 10 Cryptocurrencies - Crypto List',
  meta: [
    { name: 'description', content: 'View the top 10 cryptocurrencies by market capitalization in real-time.' }
  ]
})
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <PageHeader title="Crypto List" :show-search-button="true" />

    <main class="container mx-auto px-4 py-8">
      <LoadingSpinner v-if="pending" />

      <ErrorMessage 
        v-else-if="error" 
        title="❌ Error loading cryptocurrencies"
        :message="error.message" 
      />

      <div v-else-if="cryptos?.success" class="space-y-4">
        <h2 class="text-2xl font-bold text-white mb-6">🏆 Top 10 Cryptocurrencies by Market Cap</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <CryptoCard
            v-for="crypto in cryptos.data"
            :key="crypto.id"
            :crypto="crypto"
          />
        </div>
      </div>
    </main>

    <PageFooter />
  </div>
</template>
