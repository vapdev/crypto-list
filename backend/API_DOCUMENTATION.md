# Crypto List - Backend API

Laravel-based REST API for cryptocurrency data, integrating with CoinGecko API.

## 🚀 Features

- ✅ Top 10 cryptocurrencies by market cap
- ✅ Detailed cryptocurrency information
- ✅ Search functionality by name or symbol
- ✅ Comprehensive error handling
- ✅ CORS enabled for frontend integration
- ✅ Retry logic for API resilience
- ✅ Fully documented code

## 📋 Requirements

- PHP 8.2+
- Composer
- Laravel 11.x

## 🔧 Installation

1. **Install dependencies:**
```bash
composer install
```

2. **Environment setup:**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Optional - Configure CoinGecko API settings in `.env`:**
```env
COINGECKO_BASE_URL=https://api.coingecko.com/api/v3
COINGECKO_TIMEOUT=10
```

4. **Start the development server:**
```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

## 📡 API Endpoints

### 1. Get Top Cryptocurrencies
**Endpoint:** `GET /api/top-cryptos`

Returns the top 10 cryptocurrencies ordered by market capitalization.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "bitcoin",
      "symbol": "btc",
      "name": "Bitcoin",
      "image": "https://...",
      "current_price": 50000,
      "market_cap": 1000000000,
      "market_cap_rank": 1,
      "total_volume": 50000000,
      "price_change_percentage_24h": 2.5
    }
  ]
}
```

### 2. Get Cryptocurrency Details
**Endpoint:** `GET /api/crypto/{id}`

Returns detailed information about a specific cryptocurrency.

**Parameters:**
- `id` (required): Cryptocurrency ID (e.g., 'bitcoin', 'ethereum')

**Example:** `GET /api/crypto/bitcoin`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "bitcoin",
    "symbol": "btc",
    "name": "Bitcoin",
    "market_data": {
      "current_price": {...},
      "market_cap": {...},
      "total_volume": {...}
    },
    "description": {...}
  }
}
```

### 3. Search Cryptocurrencies
**Endpoint:** `GET /api/search?query={search_term}`

Search for cryptocurrencies by name or symbol.

**Parameters:**
- `query` (required): Search term (min: 1, max: 100 characters)

**Example:** `GET /api/search?query=bitcoin`

**Response:**
```json
{
  "success": true,
  "query": "bitcoin",
  "data": [
    {
      "id": "bitcoin",
      "name": "Bitcoin",
      "symbol": "BTC",
      "market_cap_rank": 1,
      "thumb": "https://..."
    }
  ]
}
```

## 🛡️ Error Handling

All endpoints return consistent error responses:

```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error (only in debug mode)"
}
```

**HTTP Status Codes:**
- `200` - Success
- `404` - Resource not found
- `422` - Validation error
- `503` - Service unavailable (API error)

## 🔒 Security Features

- Input validation on all endpoints
- **Rate limiting: 60 requests per minute per IP** (configured via RouteServiceProvider)
- CORS configuration for frontend integration
- Error messages respect debug mode (sensitive info hidden in production)
- Retry logic with exponential backoff (3 attempts, 100ms delay)

## 📚 Code Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           └── CryptoController.php    # API endpoints controller
├── Services/
│   └── CoinGeckoService.php            # CoinGecko API integration
config/
├── cors.php                             # CORS configuration
└── services.php                         # Third-party services config
routes/
└── api.php                              # API routes definition
```

## 🧪 Testing

The application includes comprehensive test coverage:

**Run all tests:**
```bash
php artisan test
```

**Test Coverage:**
- ✅ Feature tests for all API endpoints
- ✅ Unit tests for CoinGeckoService
- ✅ Error handling validation
- ✅ Input validation tests
- ✅ HTTP status code verification

**Test Files:**
- `tests/Feature/CryptoApiTest.php` - API endpoint integration tests
- `tests/Unit/CoinGeckoServiceTest.php` - Service layer unit tests

## 🔗 CoinGecko API

This application uses the free CoinGecko API v3.

- **API Documentation:** https://www.coingecko.com/en/api/documentation
- **Rate Limits:** 10-30 calls/minute (free tier)
- **No API key required** for basic endpoints

## 🌐 CORS Configuration

CORS is configured to allow all origins by default. For production, update `config/cors.php`:

```php
'allowed_origins' => ['https://your-frontend-domain.com'],
```

## 📝 Development Notes

- The service includes automatic retry logic (3 attempts) for resilience
- All API calls have a 10-second timeout (configurable)
- Errors are logged using Laravel's logging system
- Constructor property promotion used for cleaner code (PHP 8.0+)

## 🤝 Best Practices Implemented

✅ Service layer pattern for business logic separation  
✅ Dependency injection for testability  
✅ Comprehensive error handling with appropriate HTTP codes  
✅ Input validation using Laravel's validation  
✅ PHPDoc comments for all methods  
✅ Configuration-driven approach (no hardcoded values)  
✅ Retry logic for external API calls  
✅ Consistent JSON response structure  

## 📄 License

This project is part of a technical assessment.
