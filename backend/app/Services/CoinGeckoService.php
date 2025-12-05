<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Exception;

/**
 * CoinGecko API Service
 *
 * Service class responsible for all interactions with the CoinGecko API.
 * Handles data fetching, error handling, and retry logic for cryptocurrency data.
 *
 * CoinGecko API Documentation: https://www.coingecko.com/en/api/documentation
 *
 * @package App\Services
 */
class CoinGeckoService
{
    /**
     * Base URL for CoinGecko API
     * @var string
     */
    protected string $baseUrl;

    /**
     * Request timeout in seconds
     * @var int
     */
    protected int $timeout;

    /**
     * Initialize service with configuration
     *
     * Loads API base URL and timeout settings from config/services.php
     */
    public function __construct()
    {
        $this->baseUrl = config('services.coingecko.base_url', 'https://api.coingecko.com/api/v3');
        $this->timeout = config('services.coingecko.timeout', 10);
    }

    /**
     * Fetch top 10 cryptocurrencies by market cap
     *
     * Returns the top 10 cryptocurrencies ordered by market capitalization.
     *
     * @return array Array of cryptocurrency data
     * @throws Exception If the API request fails
     */
    public function getTopCryptos(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry(3, 100)
                ->get("{$this->baseUrl}/coins/markets", [
                    'vs_currency' => 'usd',
                    'order' => 'market_cap_desc',
                    'per_page' => 10,
                    'page' => 1,
                    'sparkline' => false,
                ]);

            if ($response->notFound()) {
                throw new Exception('CoinGecko API endpoint not found', 404);
            }

            if ($response->failed()) {
                throw new Exception(
                    'Failed to fetch cryptocurrencies from CoinGecko: ' . $response->status(),
                    $response->status()
                );
            }

            return $response->json() ?? [];
        } catch (RequestException $e) {
            report($e);
            throw new Exception('Network error while fetching cryptocurrencies: ' . $e->getMessage(), 503);
        }
    }

    /**
     * Fetch detailed data of a cryptocurrency by ID
     *
     * @throws Exception
     */
    public function getCryptoById(string $id): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry(3, 100)
                ->get("{$this->baseUrl}/coins/{$id}", [
                    'localization' => false,
                    'tickers' => false,
                    'market_data' => true,
                    'community_data' => false,
                    'developer_data' => false,
                    'sparkline' => false,
                ]);

            if ($response->notFound()) {
                throw new Exception("Cryptocurrency '{$id}' not found", 404);
            }

            if ($response->failed()) {
                throw new Exception(
                    "Failed to fetch crypto details for '{$id}': " . $response->status(),
                    $response->status()
                );
            }

            return $response->json() ?? [];
        } catch (RequestException $e) {
            report($e);
            throw new Exception("Network error while fetching crypto '{$id}': " . $e->getMessage(), 503);
        }
    }

    /**
     * Search for cryptocurrencies by name or symbol
     *
     * Searches the CoinGecko API for cryptocurrencies matching the query.
     * Returns a list of matching coins with basic information.
     *
     * @param string $query The search term (name or symbol)
     * @return array Array of matching cryptocurrencies
     * @throws Exception If the API request fails
     *
     * @example searchCrypto('bitcoin') // Returns Bitcoin and related coins
     * @example searchCrypto('btc') // Returns coins with BTC symbol
     */
    public function searchCrypto(string $query): array
    {
        try {
            // Use CoinGecko's search endpoint
            $response = Http::timeout($this->timeout)
                ->retry(3, 100)
                ->get("{$this->baseUrl}/search", [
                    'query' => $query,
                ]);

            if ($response->notFound()) {
                throw new Exception('CoinGecko search endpoint not found', 404);
            }

            if ($response->failed()) {
                throw new Exception(
                    'Failed to search cryptocurrencies: ' . $response->status(),
                    $response->status()
                );
            }

            $data = $response->json();

            // CoinGecko search returns coins, exchanges, etc.
            // We only want coins for this application
            return $data['coins'] ?? [];
        } catch (RequestException $e) {
            report($e);
            throw new Exception('Network error while searching cryptocurrencies: ' . $e->getMessage(), 503);
        }
    }
}
