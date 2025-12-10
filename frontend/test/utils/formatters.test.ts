import { describe, it, expect } from 'vitest'
import {
  formatCurrency,
  formatBillions,
  formatPercentage,
  getPriceChangeArrow,
  getPriceChangeColor,
} from '~/utils/formatters'

describe('formatters', () => {
  describe('formatCurrency', () => {
    it('formats numbers with default 2 decimals', () => {
      expect(formatCurrency(1234.56)).toBe('1,234.56')
      expect(formatCurrency(999.99)).toBe('999.99')
      expect(formatCurrency(1000000)).toBe('1,000,000.00')
    })

    it('formats numbers with custom decimals', () => {
      expect(formatCurrency(1234.5678, 4)).toBe('1,234.5678')
      expect(formatCurrency(100, 0)).toBe('100')
    })

    it('handles small numbers correctly', () => {
      expect(formatCurrency(0.001)).toBe('0.00')
      expect(formatCurrency(0.999, 3)).toBe('0.999')
    })
  })

  describe('formatBillions', () => {
    it('converts numbers to billions', () => {
      expect(formatBillions(1000000000)).toBe('1.00')
      expect(formatBillions(5500000000)).toBe('5.50')
      expect(formatBillions(123456789012)).toBe('123.46')
    })

    it('handles custom decimals', () => {
      expect(formatBillions(1234567890, 0)).toBe('1')
      expect(formatBillions(1234567890, 3)).toBe('1.235')
    })

    it('handles small numbers', () => {
      expect(formatBillions(500000000)).toBe('0.50')
      expect(formatBillions(10000000)).toBe('0.01')
    })
  })

  describe('formatPercentage', () => {
    it('formats positive percentages', () => {
      expect(formatPercentage(5.23)).toBe('5.23')
      expect(formatPercentage(12.5)).toBe('12.50')
    })

    it('formats negative percentages (returns absolute)', () => {
      expect(formatPercentage(-5.23)).toBe('5.23')
      expect(formatPercentage(-12.5)).toBe('12.50')
    })

    it('handles custom decimals', () => {
      expect(formatPercentage(5.678, 1)).toBe('5.7')
      expect(formatPercentage(5.678, 3)).toBe('5.678')
    })

    it('handles zero', () => {
      expect(formatPercentage(0)).toBe('0.00')
    })
  })

  describe('getPriceChangeArrow', () => {
    it('returns up arrow for positive values', () => {
      expect(getPriceChangeArrow(5)).toBe('↗')
      expect(getPriceChangeArrow(0.01)).toBe('↗')
    })

    it('returns down arrow for negative values', () => {
      expect(getPriceChangeArrow(-5)).toBe('↘')
      expect(getPriceChangeArrow(-0.01)).toBe('↘')
    })

    it('returns up arrow for zero', () => {
      expect(getPriceChangeArrow(0)).toBe('↗')
    })
  })

  describe('getPriceChangeColor', () => {
    it('returns green color for positive values', () => {
      expect(getPriceChangeColor(5)).toBe('text-green-400')
      expect(getPriceChangeColor(0.01)).toBe('text-green-400')
    })

    it('returns red color for negative values', () => {
      expect(getPriceChangeColor(-5)).toBe('text-red-400')
      expect(getPriceChangeColor(-0.01)).toBe('text-red-400')
    })

    it('returns green color for zero', () => {
      expect(getPriceChangeColor(0)).toBe('text-green-400')
    })
  })
})
