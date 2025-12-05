<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CoinGeckoService;
use Illuminate\Support\Facades\Http;

/**
 * CoinGecko Service Unit Tests
 *
 * Tests the CoinGeckoService class with mocked HTTP responses
 * to ensure proper handling of API responses and errors.
 */
class CoinGeckoServiceTest extends TestCase
{
    protected CoinGeckoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CoinGeckoService();
    }

    /**
     * Test that getTopCryptos returns array of cryptocurrencies
     */
    public function test_get_top_cryptos_returns_array(): void
    {
        Http::fake([
            '*/coins/markets*' => Http::response([
                [
                    'id' => 'bitcoin',
                    'symbol' => 'btc',
                    'name' => 'Bitcoin',
                    'current_price' => 50000,
                ],
                [
                    'id' => 'ethereum',
                    'symbol' => 'eth',
                    'name' => 'Ethereum',
                    'current_price' => 3000,
                ],
            ], 200),
        ]);

        $result = $this->service->getTopCryptos();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('bitcoin', $result[0]['id']);
        $this->assertEquals('ethereum', $result[1]['id']);
    }

    /**
     * Test that getTopCryptos throws exception on API failure
     */
    public function test_get_top_cryptos_throws_exception_on_failure(): void
    {
        Http::fake([
            '*/coins/markets*' => Http::response([], 500),
        ]);

        $this->expectException(\Exception::class);

        $this->service->getTopCryptos();
    }

    /**
     * Test that getCryptoById returns cryptocurrency data
     */
    public function test_get_crypto_by_id_returns_data(): void
    {
        Http::fake([
            '*/coins/bitcoin*' => Http::response([
                'id' => 'bitcoin',
                'symbol' => 'btc',
                'name' => 'Bitcoin',
                'market_data' => [
                    'current_price' => ['usd' => 50000],
                ],
            ], 200),
        ]);

        $result = $this->service->getCryptoById('bitcoin');

        $this->assertIsArray($result);
        $this->assertEquals('bitcoin', $result['id']);
        $this->assertArrayHasKey('market_data', $result);
    }

    /**
     * Test that getCryptoById throws 404 exception for non-existent crypto
     */
    public function test_get_crypto_by_id_throws_404_for_non_existent(): void
    {
        Http::fake([
            '*/coins/nonexistent*' => Http::response([], 404),
        ]);

        $this->expectException(\Exception::class);

        $this->service->getCryptoById('nonexistent');
    }

    /**
     * Test that searchCrypto returns array of results
     */
    public function test_search_crypto_returns_results(): void
    {
        Http::fake([
            '*/search*' => Http::response([
                'coins' => [
                    [
                        'id' => 'bitcoin',
                        'name' => 'Bitcoin',
                        'symbol' => 'BTC',
                        'market_cap_rank' => 1,
                    ],
                    [
                        'id' => 'bitcoin-cash',
                        'name' => 'Bitcoin Cash',
                        'symbol' => 'BCH',
                        'market_cap_rank' => 20,
                    ],
                ],
            ], 200),
        ]);

        $result = $this->service->searchCrypto('bitcoin');

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('bitcoin', $result[0]['id']);
        $this->assertEquals('bitcoin-cash', $result[1]['id']);
    }

    /**
     * Test that searchCrypto returns empty array when no results
     */
    public function test_search_crypto_returns_empty_array_when_no_results(): void
    {
        Http::fake([
            '*/search*' => Http::response([
                'coins' => [],
            ], 200),
        ]);

        $result = $this->service->searchCrypto('nonexistent');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test that searchCrypto throws exception on API failure
     */
    public function test_search_crypto_throws_exception_on_failure(): void
    {
        Http::fake([
            '*/search*' => Http::response([], 500),
        ]);

        $this->expectException(\Exception::class);

        $this->service->searchCrypto('bitcoin');
    }

    /**
     * Test that service handles network errors
     */
    public function test_service_handles_network_errors(): void
    {
        Http::fake([
            '*/coins/markets*' => function () {
                throw new \Illuminate\Http\Client\ConnectionException('Connection timeout');
            },
        ]);

        $this->expectException(\Exception::class);

        $this->service->getTopCryptos();
    }
}
