import { describe, it, expect } from 'vitest'
import { mountSuspended } from '@nuxt/test-utils/runtime'
import CryptoCard from '~/components/CryptoCard.vue'

describe('CryptoCard', () => {
  const mockCrypto = {
    id: 'bitcoin',
    symbol: 'btc',
    name: 'Bitcoin',
    image: 'https://example.com/bitcoin.png',
    current_price: 50000,
    market_cap: 1000000000000,
    market_cap_rank: 1,
    price_change_percentage_24h: 5.23,
    total_volume: 50000000000,
    high_24h: 51000,
    low_24h: 49000,
  }

  it('renders crypto information', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: mockCrypto,
      },
    })
    
    expect(component.text()).toContain('Bitcoin')
    expect(component.text()).toContain('btc')
  })

  it('displays market cap rank', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: mockCrypto,
      },
    })
    
    expect(component.text()).toContain('#1')
  })

  it('displays current price', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: mockCrypto,
      },
    })
    
    expect(component.text()).toContain('Current Price')
    expect(component.text()).toContain('50,000.00')
  })

  it('displays 24h change with positive value', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: mockCrypto,
      },
    })
    
    expect(component.text()).toContain('24h Change')
    expect(component.text()).toContain('5.23')
    expect(component.html()).toContain('text-green-400')
  })

  it('displays 24h change with negative value', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: {
          ...mockCrypto,
          price_change_percentage_24h: -3.5,
        },
      },
    })
    
    expect(component.text()).toContain('3.50')
    expect(component.html()).toContain('text-red-400')
  })

  it('displays market cap', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: mockCrypto,
      },
    })
    
    expect(component.text()).toContain('Market Cap')
  })

  it('renders as a link to detail page', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: mockCrypto,
      },
    })
    
    expect(component.html()).toContain(`/crypto/${mockCrypto.id}`)
  })

  it('has hover effects', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: mockCrypto,
      },
    })
    
    expect(component.html()).toContain('hover:border-blue-500')
    expect(component.html()).toContain('hover:shadow-xl')
  })

  it('displays crypto image', async () => {
    const component = await mountSuspended(CryptoCard, {
      props: {
        crypto: mockCrypto,
      },
    })
    
    expect(component.html()).toContain(mockCrypto.image)
    expect(component.html()).toContain(`alt="${mockCrypto.name}"`)
  })
})
