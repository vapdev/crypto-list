<script setup lang="ts">
import { formatCurrency, formatBillions, formatPercentage, getPriceChangeArrow, getPriceChangeColor } from '~/utils/formatters'

const route = useRoute()
const cryptoId = route.params.id as string

// Fetch crypto details using composable
const { getCryptoById } = useCryptoApi()
const { data: crypto, pending, error } = await getCryptoById(cryptoId)

// SEO meta tags
useHead({
  title: () => crypto.value?.success ? `${crypto.value.data.name} (${crypto.value.data.symbol.toUpperCase()}) - Crypto List` : 'Crypto Details',
  meta: [
    { name: 'description', content: () => crypto.value?.success ? `Detailed information about ${crypto.value.data.name}` : 'Cryptocurrency details' }
  ]
})
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <PageHeader title="Cryptocurrency Details" :show-back-button="true" />

    <main class="container mx-auto px-4 py-8">
      <LoadingSpinner v-if="pending" />

      <ErrorMessage 
        v-else-if="error || !crypto?.success"
        title="❌ Error loading cryptocurrency"
        :message="error?.message || crypto?.message || 'Cryptocurrency not found'"
        :show-return-link="true"
      />

      <!-- Success State -->
      <div v-else-if="crypto?.success" class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-8 mb-6">
          <div class="flex items-start gap-6">
            <img 
              :src="crypto.data.image?.large || crypto.data.image?.small" 
              :alt="crypto.data.name"
              class="w-24 h-24 rounded-full"
            />
            <div class="flex-1">
              <h1 class="text-4xl font-bold text-white mb-2">
                {{ crypto.data.name }}
              </h1>
              <p class="text-xl text-gray-400 uppercase mb-4">
                {{ crypto.data.symbol }}
              </p>
              <div class="flex flex-wrap gap-2">
                <span v-if="crypto.data.market_cap_rank" class="bg-blue-600 text-white px-3 py-1 rounded text-sm font-semibold">
                  Rank #{{ crypto.data.market_cap_rank }}
                </span>
                <a 
                  v-if="crypto.data.links?.homepage?.[0]"
                  :href="crypto.data.links.homepage[0]" 
                  target="_blank"
                  class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm transition-colors"
                >
                  🌐 Website
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Price Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <StatCard 
            label="Current Price" 
            :value="'$' + formatCurrency(crypto.data.market_data.current_price.usd)"
          >
            <p 
              v-if="crypto.data.market_data.price_change_percentage_24h"
              :class="getPriceChangeColor(crypto.data.market_data.price_change_percentage_24h)"
              class="text-lg font-semibold flex items-center gap-1 mt-2"
            >
              <span>{{ getPriceChangeArrow(crypto.data.market_data.price_change_percentage_24h) }}</span>
              {{ formatPercentage(crypto.data.market_data.price_change_percentage_24h) }}% (24h)
            </p>
          </StatCard>

          <StatCard 
            label="Market Cap" 
            :value="'$' + formatBillions(crypto.data.market_data.market_cap.usd) + 'B'"
          >
            <p class="text-gray-400 text-sm mt-2">
              Rank #{{ crypto.data.market_cap_rank }}
            </p>
          </StatCard>
        </div>

        <!-- Additional Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <StatCard 
            label="24h Volume" 
            :value="'$' + formatBillions(crypto.data.market_data.total_volume.usd) + 'B'"
          />

          <StatCard 
            label="24h High" 
            :value="'$' + formatCurrency(crypto.data.market_data.high_24h.usd)"
            value-color="text-green-400"
          />

          <StatCard 
            label="24h Low" 
            :value="'$' + formatCurrency(crypto.data.market_data.low_24h.usd)"
            value-color="text-red-400"
          />
        </div>

        <!-- Description -->
        <div v-if="crypto.data.description?.en" class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-6">
          <h3 class="text-xl font-bold text-white mb-4">About {{ crypto.data.name }}</h3>
          <div 
            class="text-gray-300 prose prose-invert max-w-none"
            v-html="crypto.data.description.en"
          ></div>
        </div>
      </div>
    </main>
  </div>
</template>
