/**
 * Composable for cryptocurrency API calls
 * Centralizes all API logic for better maintainability
 */

import type { ApiResponse, CryptoListItem, CryptoDetail, SearchResult } from '~/types/crypto'

export const useCryptoApi = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBaseUrl

  /**
   * Fetch top 10 cryptocurrencies by market cap
   */
  const getTopCryptos = async () => {
    return useFetch<ApiResponse<CryptoListItem[]>>('/api/top-cryptos', {
      baseURL,
      lazy: false,
    })
  }

  /**
   * Fetch detailed information about a specific cryptocurrency
   */
  const getCryptoById = async (id: string) => {
    return useFetch<ApiResponse<CryptoDetail>>(`/api/crypto/${id}`, {
      baseURL,
      lazy: false,
    })
  }

  /**
   * Search cryptocurrencies by name or symbol
   */
  const searchCrypto = async (query: string) => {
    return useFetch<ApiResponse<SearchResult[]>>('/api/search', {
      baseURL,
      query: { query },
    })
  }

  return {
    getTopCryptos,
    getCryptoById,
    searchCrypto,
  }
}
