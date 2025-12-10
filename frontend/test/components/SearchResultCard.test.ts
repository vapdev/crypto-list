import { describe, it, expect } from 'vitest'
import { mountSuspended } from '@nuxt/test-utils/runtime'
import SearchResultCard from '~/components/SearchResultCard.vue'

describe('SearchResultCard', () => {
  const mockCoin = {
    id: 'ethereum',
    name: 'Ethereum',
    symbol: 'eth',
    thumb: 'https://example.com/ethereum.png',
    market_cap_rank: 2,
  }

  it('renders coin information', async () => {
    const component = await mountSuspended(SearchResultCard, {
      props: {
        coin: mockCoin,
      },
    })
    
    expect(component.text()).toContain('Ethereum')
    expect(component.text()).toContain('eth')
  })

  it('displays market cap rank', async () => {
    const component = await mountSuspended(SearchResultCard, {
      props: {
        coin: mockCoin,
      },
    })
    
    expect(component.text()).toContain('#2')
  })

  it('does not show rank badge when rank is null', async () => {
    const component = await mountSuspended(SearchResultCard, {
      props: {
        coin: {
          ...mockCoin,
          market_cap_rank: null,
        },
      },
    })
    
    expect(component.html()).not.toContain('bg-blue-600')
  })

  it('renders coin image', async () => {
    const component = await mountSuspended(SearchResultCard, {
      props: {
        coin: mockCoin,
      },
    })
    
    expect(component.html()).toContain(mockCoin.thumb)
    expect(component.html()).toContain(`alt="${mockCoin.name}"`)
  })

  it('renders as a link to detail page', async () => {
    const component = await mountSuspended(SearchResultCard, {
      props: {
        coin: mockCoin,
      },
    })
    
    expect(component.html()).toContain(`/crypto/${mockCoin.id}`)
  })

  it('has hover effects', async () => {
    const component = await mountSuspended(SearchResultCard, {
      props: {
        coin: mockCoin,
      },
    })
    
    expect(component.html()).toContain('hover:border-blue-500')
    expect(component.html()).toContain('group-hover:text-blue-400')
  })

  it('shows "View details" hint', async () => {
    const component = await mountSuspended(SearchResultCard, {
      props: {
        coin: mockCoin,
      },
    })
    
    expect(component.text()).toContain('View details')
  })
})
