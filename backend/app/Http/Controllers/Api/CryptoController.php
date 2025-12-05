<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CoinGeckoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

/**
 * CryptoController
 *
 * Handles all cryptocurrency-related API endpoints.
 * Integrates with CoinGecko API through CoinGeckoService.
 *
 * @package App\Http\Controllers\Api
 */
class CryptoController extends Controller
{
    /**
     * Constructor with dependency injection
     *
     * @param CoinGeckoService $coinGeckoService Service for CoinGecko API integration
     */
    public function __construct(
        protected CoinGeckoService $coinGeckoService
    ) {}

    /**
     * Get top 10 cryptocurrencies by market cap
     *
     * Returns the top 10 cryptocurrencies ordered by market capitalization.
     * Used for the homepage display.
     *
     * @return JsonResponse
     *
     * @example GET /api/top-cryptos
     * @response {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": "bitcoin",
     *       "symbol": "btc",
     *       "name": "Bitcoin",
     *       "current_price": 50000,
     *       "market_cap": 1000000000,
     *       ...
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        try {
            $cryptos = $this->coinGeckoService->getTopCryptos();

            return response()->json([
                'success' => true,
                'data' => $cryptos,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cryptocurrencies',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * Get detailed information about a specific cryptocurrency
     *
     * Returns comprehensive data including price, volume, market cap,
     * and other market data for a specific cryptocurrency.
     *
     * @param string $id The cryptocurrency ID (e.g., 'bitcoin', 'ethereum')
     * @return JsonResponse
     *
     * @example GET /api/crypto/bitcoin
     * @response {
     *   "success": true,
     *   "data": {
     *     "id": "bitcoin",
     *     "name": "Bitcoin",
     *     "market_data": {...},
     *     ...
     *   }
     * }
     */
    public function show(string $id): JsonResponse
    {
        try {
            $crypto = $this->coinGeckoService->getCryptoById($id);

            return response()->json([
                'success' => true,
                'data' => $crypto,
            ]);
        } catch (Exception $e) {
            $statusCode = $e->getCode() === 404
                ? Response::HTTP_NOT_FOUND
                : Response::HTTP_SERVICE_UNAVAILABLE;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => config('app.debug') ? $e->getTraceAsString() : null,
            ], $statusCode);
        }
    }

    /**
     * Search cryptocurrencies by name or symbol
     *
     * Allows users to search for cryptocurrencies using a query string.
     * Searches both name and symbol fields.
     *
     * @param Request $request The HTTP request containing 'query' parameter
     * @return JsonResponse
     *
     * @example GET /api/search?query=bitcoin
     * @response {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": "bitcoin",
     *       "name": "Bitcoin",
     *       "symbol": "btc",
     *       ...
     *     }
     *   ]
     * }
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'query' => 'required|string|min:1|max:100',
            ]);

            $results = $this->coinGeckoService->searchCrypto($validated['query']);

            return response()->json([
                'success' => true,
                'data' => $results,
                'query' => $validated['query'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid search query',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search cryptocurrencies',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
