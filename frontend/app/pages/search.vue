<script setup lang="ts">
import type { ApiResponse, SearchResult } from '~/types/crypto'

const searchQuery = ref('')
const searchResults = ref<ApiResponse<SearchResult[]> | null>(null)
const isSearching = ref(false)
const searchError = ref<string | null>(null)

const { searchCrypto } = useCryptoApi()

// Debounced search function
let searchTimeout: any
const performSearch = async () => {
  if (!searchQuery.value || searchQuery.value.length < 2) {
    searchResults.value = null
    return
  }

  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(async () => {
    isSearching.value = true
    searchError.value = null

    try {
      const { data, error } = await searchCrypto(searchQuery.value)

      if (error.value) {
        searchError.value = 'Failed to search cryptocurrencies'
      } else if (data.value?.success) {
        searchResults.value = data.value
      }
    } catch (err) {
      searchError.value = 'An error occurred while searching'
    } finally {
      isSearching.value = false
    }
  }, 500)
}

// Watch search query
watch(searchQuery, performSearch)

// SEO
useHead({
  title: 'Search Cryptocurrencies - Crypto List',
  meta: [
    { name: 'description', content: 'Search for cryptocurrencies by name or symbol' }
  ]
})
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <PageHeader title="🔍 Search Cryptocurrencies" :show-back-button="true" />

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <div class="max-w-3xl mx-auto">
        <!-- Search Input -->
        <div class="mb-8">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by name or symbol (e.g., Bitcoin, BTC)..."
              class="w-full bg-gray-800/50 border border-gray-700 text-white px-6 py-4 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
            />
            <div v-if="isSearching" class="absolute right-4 top-1/2 transform -translate-y-1/2">
              <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-blue-500"></div>
            </div>
          </div>
          <p class="text-gray-400 text-sm mt-2">
            Type at least 2 characters to search
          </p>
        </div>

        <ErrorMessage 
          v-if="searchError"
          title="Error"
          :message="searchError"
          class="mb-6"
        />

        <!-- Empty State -->
        <div v-if="!searchQuery" class="text-center py-12">
          <div class="text-6xl mb-4">🔍</div>
          <h2 class="text-xl font-bold text-white mb-2">Start searching</h2>
          <p class="text-gray-400">Enter a cryptocurrency name or symbol above</p>
        </div>

        <!-- No Results -->
        <div v-else-if="searchResults && searchResults.data.length === 0" class="text-center py-12">
          <div class="text-6xl mb-4">😕</div>
          <h2 class="text-xl font-bold text-white mb-2">No results found</h2>
          <p class="text-gray-400">Try searching for a different term</p>
        </div>

        <!-- Search Results -->
        <div v-else-if="searchResults?.success" class="space-y-4">
          <h2 class="text-lg font-semibold text-gray-400 mb-4">
            Found {{ searchResults.data.length }} result{{ searchResults.data.length !== 1 ? 's' : '' }}
          </h2>
          
          <div class="grid grid-cols-1 gap-4">
            <SearchResultCard
              v-for="coin in searchResults.data"
              :key="coin.id"
              :coin="coin"
            />
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
