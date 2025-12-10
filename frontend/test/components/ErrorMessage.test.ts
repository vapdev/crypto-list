import { describe, it, expect } from 'vitest'
import { mountSuspended } from '@nuxt/test-utils/runtime'
import ErrorMessage from '~/components/ErrorMessage.vue'

describe('ErrorMessage', () => {
  it('renders with required message prop', async () => {
    const component = await mountSuspended(ErrorMessage, {
      props: {
        message: 'Test error message',
      },
    })
    
    expect(component.text()).toContain('Test error message')
  })

  it('renders default title when not provided', async () => {
    const component = await mountSuspended(ErrorMessage, {
      props: {
        message: 'Test error',
      },
    })
    
    expect(component.text()).toContain('❌ Error')
  })

  it('renders custom title when provided', async () => {
    const component = await mountSuspended(ErrorMessage, {
      props: {
        title: 'Custom Error Title',
        message: 'Test error',
      },
    })
    
    expect(component.text()).toContain('Custom Error Title')
  })

  it('does not show return link by default', async () => {
    const component = await mountSuspended(ErrorMessage, {
      props: {
        message: 'Test error',
      },
    })
    
    expect(component.text()).not.toContain('Return to homepage')
  })

  it('shows return link when showReturnLink is true', async () => {
    const component = await mountSuspended(ErrorMessage, {
      props: {
        message: 'Test error',
        showReturnLink: true,
      },
    })
    
    expect(component.text()).toContain('Return to homepage')
  })

  it('has correct styling classes', async () => {
    const component = await mountSuspended(ErrorMessage, {
      props: {
        message: 'Test error',
      },
    })
    
    expect(component.html()).toContain('bg-red-900/20')
    expect(component.html()).toContain('border-red-500')
    expect(component.html()).toContain('text-red-400')
  })
})
