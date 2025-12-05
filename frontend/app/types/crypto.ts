/**
 * TypeScript interfaces for Cryptocurrency API responses
 */

export interface CryptoListItem {
  id: string
  symbol: string
  name: string
  image: string
  current_price: number
  market_cap: number
  market_cap_rank: number
  price_change_percentage_24h: number
  total_volume: number
  high_24h: number
  low_24h: number
}

export interface CryptoDetail {
  id: string
  symbol: string
  name: string
  description: {
    en: string
  }
  image: {
    thumb: string
    small: string
    large: string
  }
  market_cap_rank: number
  market_data: {
    current_price: {
      usd: number
    }
    market_cap: {
      usd: number
    }
    total_volume: {
      usd: number
    }
    high_24h: {
      usd: number
    }
    low_24h: {
      usd: number
    }
    price_change_percentage_24h: number
  }
  links: {
    homepage: string[]
  }
}

export interface SearchResult {
  id: string
  name: string
  symbol: string
  thumb: string
  market_cap_rank: number | null
}

export interface ApiResponse<T> {
  success: boolean
  data: T
  message?: string
  query?: string
}
