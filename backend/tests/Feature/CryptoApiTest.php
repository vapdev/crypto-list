<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\CoinGeckoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

/**
 * Crypto API Feature Tests
 *
 * Tests all cryptocurrency API endpoints with mocked CoinGecko service
 * to avoid hitting the actual API during tests.
 */
class CryptoApiTest extends TestCase
{
    /**
     * Test that top cryptos endpoint returns successful response
     */
    public function test_top_cryptos_returns_successful_response(): void
    {
        // Mock the CoinGecko service
        $mockService = Mockery::mock(CoinGeckoService::class);
        $mockService->shouldReceive('getTopCryptos')
            ->once()
            ->andReturn([
                [
                    'id' => 'bitcoin',
                    'symbol' => 'btc',
                    'name' => 'Bitcoin',
                    'current_price' => 50000,
                    'market_cap' => 1000000000,
                ],
                [
                    'id' => 'ethereum',
                    'symbol' => 'eth',
                    'name' => 'Ethereum',
                    'current_price' => 3000,
                    'market_cap' => 500000000,
                ],
            ]);

        $this->app->instance(CoinGeckoService::class, $mockService);

        // Make request to the endpoint
        $response = $this->getJson('/api/top-cryptos');

        // Assert response structure and status
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'symbol',
                        'name',
                        'current_price',
                        'market_cap',
                    ],
                ],
            ]);
    }

    /**
     * Test that crypto detail endpoint returns successful response
     */
    public function test_crypto_detail_returns_successful_response(): void
    {
        $mockService = Mockery::mock(CoinGeckoService::class);
        $mockService->shouldReceive('getCryptoById')
            ->with('bitcoin')
            ->once()
            ->andReturn([
                'id' => 'bitcoin',
                'symbol' => 'btc',
                'name' => 'Bitcoin',
                'market_data' => [
                    'current_price' => ['usd' => 50000],
                    'market_cap' => ['usd' => 1000000000],
                ],
            ]);

        $this->app->instance(CoinGeckoService::class, $mockService);

        $response = $this->getJson('/api/crypto/bitcoin');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'symbol',
                    'name',
                    'market_data',
                ],
            ]);
    }

    /**
     * Test that crypto detail endpoint returns 404 for non-existent crypto
     */
    public function test_crypto_detail_returns_404_for_non_existent_crypto(): void
    {
        $mockService = Mockery::mock(CoinGeckoService::class);
        $mockService->shouldReceive('getCryptoById')
            ->with('nonexistent')
            ->once()
            ->andThrow(new \Exception("Cryptocurrency 'nonexistent' not found", 404));

        $this->app->instance(CoinGeckoService::class, $mockService);

        $response = $this->getJson('/api/crypto/nonexistent');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test that search endpoint returns successful response
     */
    public function test_search_returns_successful_response(): void
    {
        $mockService = Mockery::mock(CoinGeckoService::class);
        $mockService->shouldReceive('searchCrypto')
            ->with('bitcoin')
            ->once()
            ->andReturn([
                [
                    'id' => 'bitcoin',
                    'name' => 'Bitcoin',
                    'symbol' => 'BTC',
                    'market_cap_rank' => 1,
                ],
            ]);

        $this->app->instance(CoinGeckoService::class, $mockService);

        $response = $this->getJson('/api/search?query=bitcoin');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'query' => 'bitcoin',
            ])
            ->assertJsonStructure([
                'success',
                'query',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'symbol',
                    ],
                ],
            ]);
    }

    /**
     * Test that search endpoint validates required query parameter
     */
    public function test_search_validates_required_query_parameter(): void
    {
        $response = $this->getJson('/api/search');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid search query',
            ])
            ->assertJsonStructure([
                'errors' => [
                    'query',
                ],
            ]);
    }

    /**
     * Test that search endpoint validates query parameter length
     */
    public function test_search_validates_query_parameter_length(): void
    {
        // Test with string longer than 100 characters
        $longQuery = str_repeat('a', 101);
        $response = $this->getJson('/api/search?query=' . $longQuery);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'query',
                ],
            ]);
    }

    /**
     * Test that API handles service errors gracefully
     */
    public function test_api_handles_service_errors_gracefully(): void
    {
        $mockService = Mockery::mock(CoinGeckoService::class);
        $mockService->shouldReceive('getTopCryptos')
            ->once()
            ->andThrow(new \Exception('Network error', 503));

        $this->app->instance(CoinGeckoService::class, $mockService);

        $response = $this->getJson('/api/top-cryptos');

        $response->assertStatus(503)
            ->assertJson([
                'success' => false,
                'message' => 'Failed to fetch cryptocurrencies',
            ]);
    }

    /**
     * Clean up Mockery after each test
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
