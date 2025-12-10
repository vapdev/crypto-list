import { describe, it, expect } from 'vitest'
import { mountSuspended } from '@nuxt/test-utils/runtime'
import LoadingSpinner from '~/components/LoadingSpinner.vue'

describe('LoadingSpinner', () => {
  it('renders with default medium size', async () => {
    const component = await mountSuspended(LoadingSpinner)
    
    expect(component.html()).toContain('animate-spin')
    expect(component.html()).toContain('h-16 w-16') // default md size
  })

  it('renders with small size', async () => {
    const component = await mountSuspended(LoadingSpinner, {
      props: { size: 'sm' },
    })
    
    expect(component.html()).toContain('h-8 w-8')
  })

  it('renders with large size', async () => {
    const component = await mountSuspended(LoadingSpinner, {
      props: { size: 'lg' },
    })
    
    expect(component.html()).toContain('h-24 w-24')
  })

  it('has correct structure', async () => {
    const component = await mountSuspended(LoadingSpinner)
    
    // Check container
    expect(component.html()).toContain('flex justify-center items-center')
    
    // Check spinner element
    expect(component.html()).toContain('rounded-full')
    expect(component.html()).toContain('border-blue-500')
  })
})
