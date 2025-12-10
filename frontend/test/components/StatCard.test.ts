import { describe, it, expect } from 'vitest'
import { mountSuspended } from '@nuxt/test-utils/runtime'
import StatCard from '~/components/StatCard.vue'

describe('StatCard', () => {
  it('renders label and value', async () => {
    const component = await mountSuspended(StatCard, {
      props: {
        label: 'Current Price',
        value: '$50,000.00',
      },
    })
    
    expect(component.text()).toContain('Current Price')
    expect(component.text()).toContain('$50,000.00')
  })

  it('applies default white color to value', async () => {
    const component = await mountSuspended(StatCard, {
      props: {
        label: 'Test',
        value: '100',
      },
    })
    
    expect(component.html()).toContain('text-white')
  })

  it('applies custom color to value', async () => {
    const component = await mountSuspended(StatCard, {
      props: {
        label: 'Test',
        value: '100',
        valueColor: 'text-green-400',
      },
    })
    
    expect(component.html()).toContain('text-green-400')
  })

  it('renders slot content', async () => {
    const component = await mountSuspended(StatCard, {
      props: {
        label: 'Test',
        value: '100',
      },
      slots: {
        default: '<p class="test-slot">Additional info</p>',
      },
    })
    
    expect(component.text()).toContain('Additional info')
    expect(component.html()).toContain('test-slot')
  })

  it('has correct card styling', async () => {
    const component = await mountSuspended(StatCard, {
      props: {
        label: 'Test',
        value: '100',
      },
    })
    
    expect(component.html()).toContain('bg-gray-800/50')
    expect(component.html()).toContain('backdrop-blur-sm')
    expect(component.html()).toContain('rounded-xl')
  })
})
