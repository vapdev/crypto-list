import { describe, it, expect } from 'vitest'
import { mountSuspended } from '@nuxt/test-utils/runtime'
import PageHeader from '~/components/PageHeader.vue'

describe('PageHeader', () => {
  it('renders title prop', async () => {
    const component = await mountSuspended(PageHeader, {
      props: {
        title: 'Test Title',
      },
    })
    
    expect(component.text()).toContain('Test Title')
  })

  it('does not show back button by default', async () => {
    const component = await mountSuspended(PageHeader, {
      props: {
        title: 'Test',
      },
    })
    
    expect(component.text()).not.toContain('Back to Home')
  })

  it('shows back button when showBackButton is true', async () => {
    const component = await mountSuspended(PageHeader, {
      props: {
        title: 'Test',
        showBackButton: true,
      },
    })
    
    expect(component.text()).toContain('Back to Home')
  })

  it('does not show search button by default', async () => {
    const component = await mountSuspended(PageHeader, {
      props: {
        title: 'Test',
      },
    })
    
    expect(component.text()).not.toContain('Search Cryptos')
  })

  it('shows search button when showSearchButton is true', async () => {
    const component = await mountSuspended(PageHeader, {
      props: {
        title: 'Test',
        showSearchButton: true,
      },
    })
    
    expect(component.text()).toContain('Search Cryptos')
  })

  it('has sticky header styling', async () => {
    const component = await mountSuspended(PageHeader, {
      props: {
        title: 'Test',
      },
    })
    
    expect(component.html()).toContain('sticky top-0')
    expect(component.html()).toContain('backdrop-blur-sm')
  })

  it('shows emoji when no back button', async () => {
    const component = await mountSuspended(PageHeader, {
      props: {
        title: 'Test',
        showBackButton: false,
      },
    })
    
    expect(component.text()).toContain('💰')
  })
})
