import { describe, it, expect, beforeEach } from 'vitest'
import { useCryptoApi } from '~/composables/useCryptoApi'

describe('useCryptoApi', () => {
  let api: ReturnType<typeof useCryptoApi>

  beforeEach(() => {
    api = useCryptoApi()
  })

  it('should export all required methods', () => {
    expect(api).toHaveProperty('getTopCryptos')
    expect(api).toHaveProperty('getCryptoById')
    expect(api).toHaveProperty('searchCrypto')
  })

  it('getTopCryptos should be a function', () => {
    expect(typeof api.getTopCryptos).toBe('function')
  })

  it('getCryptoById should be a function', () => {
    expect(typeof api.getCryptoById).toBe('function')
  })

  it('searchCrypto should be a function', () => {
    expect(typeof api.searchCrypto).toBe('function')
  })

  // Note: Actual API calls are tested in E2E tests
  // Unit tests verify composable structure and exports
})
