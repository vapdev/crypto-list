<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CryptoController;

/**
 * Cryptocurrency API Routes
 *
 * All routes return JSON responses with the following structure:
 * Success: { "success": true, "data": [...] }
 * Error: { "success": false, "message": "...", "error": "..." }
 *
 * Rate Limiting: 60 requests per minute per IP
 */

// Rate limiting is automatically applied via bootstrap/app.php (60 requests/minute)

// Get top 10 cryptocurrencies by market cap
Route::get('/top-cryptos', [CryptoController::class, 'index']);

// Search cryptocurrencies by name or symbol
Route::get('/search', [CryptoController::class, 'search']);

// Get detailed information about a specific cryptocurrency
Route::get('/crypto/{id}', [CryptoController::class, 'show']);
