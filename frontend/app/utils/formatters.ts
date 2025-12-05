/**
 * Utility functions for formatting numbers and currencies
 */

/**
 * Format a number as USD currency
 */
export const formatCurrency = (value: number, decimals: number = 2): string => {
  return value.toLocaleString('en-US', {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals,
  })
}

/**
 * Format a number in billions with 'B' suffix
 */
export const formatBillions = (value: number, decimals: number = 2): string => {
  return (value / 1e9).toFixed(decimals)
}

/**
 * Format percentage with proper sign and decimals
 */
export const formatPercentage = (value: number, decimals: number = 2): string => {
  return Math.abs(value).toFixed(decimals)
}

/**
 * Get arrow indicator based on positive/negative value
 */
export const getPriceChangeArrow = (value: number): string => {
  return value >= 0 ? '↗' : '↘'
}

/**
 * Get color class based on positive/negative value
 */
export const getPriceChangeColor = (value: number): string => {
  return value >= 0 ? 'text-green-400' : 'text-red-400'
}
