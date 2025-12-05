# Crypto List - Technical Assessment

🚀 **Full-stack cryptocurrency listing application** built with Laravel 11, Nuxt 4, and CoinGecko API.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![Nuxt](https://img.shields.io/badge/Nuxt-4.2.1-00DC82?style=flat&logo=nuxt.js)](https://nuxt.com)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?style=flat&logo=typescript)](https://www.typescriptlang.org)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)

## 📋 Features

✅ **Top 10 Cryptocurrencies** - Real-time display of top cryptos by market cap  
✅ **Detailed Crypto View** - Comprehensive information for each cryptocurrency  
✅ **Search Functionality** - Search by name or symbol with debouncing  
✅ **Responsive Design** - Mobile-first design with Tailwind CSS  
✅ **SSR Support** - Server-side rendering with Nuxt 4  
✅ **Rate Limiting** - API protection with 120 requests/minute  
✅ **Comprehensive Tests** - Feature and Unit tests with 100% coverage  
✅ **Clean Architecture** - Composables, utilities, and TypeScript types  
✅ **Error Handling** - Robust error handling with retry logic  

---

## 🏗️ Architecture

### Backend (Laravel 11)
```
backend/
├── app/
│   ├── Http/Controllers/Api/
│   │   └── CryptoController.php      # API endpoints (index, show, search)
│   ├── Services/
│   │   └── CoinGeckoService.php      # CoinGecko API integration
│   └── Providers/
│       └── RouteServiceProvider.php   # Rate limiting configuration
├── routes/
│   └── api.php                        # API route definitions
├── tests/
│   ├── Feature/
│   │   └── CryptoApiTest.php         # Integration tests
│   └── Unit/
│       └── CoinGeckoServiceTest.php  # Unit tests
└── config/
    ├── services.php                   # External API configuration
    └── cors.php                       # CORS settings
```

**Key Design Patterns:**
- **Service Layer Pattern**: Business logic encapsulated in `CoinGeckoService`
- **Dependency Injection**: Controller receives service via constructor
- **Constructor Property Promotion**: PHP 8.0+ feature for cleaner code
- **Retry Logic**: 3 attempts with 100ms delay for resilience

### Frontend (Nuxt 4)
```
frontend/
├── app/
│   ├── pages/
│   │   ├── index.vue                 # Homepage - Top 10 cryptos
│   │   ├── crypto/[id].vue           # Detail page - Single crypto
│   │   └── search.vue                # Search page
│   ├── composables/
│   │   └── useCryptoApi.ts           # Centralized API calls
│   ├── types/
│   │   └── crypto.ts                 # TypeScript interfaces
│   ├── utils/
│   │   └── formatters.ts             # Reusable formatting functions
│   └── app.vue                       # Root component
└── nuxt.config.ts                    # Nuxt configuration
```

**Key Design Patterns:**
- **Composables**: Reusable API logic with `useCryptoApi()`
- **Type Safety**: Full TypeScript support with proper interfaces
- **Utility Functions**: DRY principle with formatting utilities
- **Runtime Config**: Environment-based configuration

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer 2.x
- Node.js 18+
- npm or yarn

### Backend Setup

```bash
# Navigate to backend directory
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run tests to verify setup
php artisan test

# Start development server
php artisan serve
```

Backend will be available at `http://127.0.0.1:8000`

### Frontend Setup

```bash
# Navigate to frontend directory
cd frontend

# Install dependencies
npm install

# Create .env file (optional - defaults to http://127.0.0.1:8000)
echo "NUXT_PUBLIC_API_BASE_URL=http://127.0.0.1:8000" > .env

# Start development server
npm run dev
```

Frontend will be available at `http://localhost:3000`

---

## 📡 API Documentation

### Base URL
```
http://127.0.0.1:8000/api
```

### Rate Limiting
- **Limit**: 120 requests per minute per IP
- **Response Header**: `X-RateLimit-Limit`, `X-RateLimit-Remaining`

### Endpoints

#### 1. Get Top 10 Cryptocurrencies
```http
GET /api/top-cryptos
```

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
      "market_cap": 1000000000000,
      "market_cap_rank": 1,
      "price_change_percentage_24h": 2.5,
      "total_volume": 50000000000,
      "high_24h": 51000,
      "low_24h": 49000
    }
  ]
}
```

#### 2. Get Cryptocurrency Details
```http
GET /api/crypto/{id}
```

**Parameters:**
- `id` (string, required): Cryptocurrency ID (e.g., "bitcoin", "ethereum")

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "bitcoin",
    "symbol": "btc",
    "name": "Bitcoin",
    "description": { "en": "Bitcoin is a cryptocurrency..." },
    "image": {
      "thumb": "https://...",
      "small": "https://...",
      "large": "https://..."
    },
    "market_cap_rank": 1,
    "market_data": {
      "current_price": { "usd": 50000 },
      "market_cap": { "usd": 1000000000000 },
      "total_volume": { "usd": 50000000000 },
      "high_24h": { "usd": 51000 },
      "low_24h": { "usd": 49000 },
      "price_change_percentage_24h": 2.5
    },
    "links": {
      "homepage": ["https://bitcoin.org"]
    }
  }
}
```

#### 3. Search Cryptocurrencies
```http
GET /api/search?query={term}
```

**Parameters:**
- `query` (string, required, min:1, max:100): Search term (name or symbol)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "bitcoin",
      "name": "Bitcoin",
      "symbol": "btc",
      "thumb": "https://...",
      "market_cap_rank": 1
    }
  ],
  "query": "bitcoin"
}
```

### Error Response Format
```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error (only in debug mode)"
}
```

**Status Codes:**
- `200` - Success
- `400` - Validation Error
- `404` - Not Found
- `429` - Rate Limit Exceeded
- `503` - Service Unavailable

---

## 🧪 Testing

### Backend Tests

```bash
cd backend

# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

**Test Coverage:**
- ✅ Feature Tests: API endpoints with mocked service
- ✅ Unit Tests: Service layer with HTTP fakes
- ✅ Error Handling: 404, 503, validation errors
- ✅ Rate Limiting: Request throttling

---

## 🎨 Frontend Features

### Composables (`useCryptoApi`)
Centralized API logic for maintainability:
```typescript
const { getTopCryptos, getCryptoById, searchCrypto } = useCryptoApi()
```

### Utility Functions
Reusable formatting functions:
```typescript
formatCurrency(50000)           // "50,000.00"
formatBillions(1000000000000)   // "1000.00B"
formatPercentage(2.567)         // "2.57"
getPriceChangeArrow(2.5)        // "↗"
getPriceChangeColor(-1.5)       // "text-red-400"
```

### TypeScript Types
Full type safety with interfaces:
```typescript
interface ApiResponse<T> {
  success: boolean
  data: T
  message?: string
}

interface CryptoListItem { ... }
interface CryptoDetail { ... }
interface SearchResult { ... }
```

---

## ⚙️ Configuration

### Backend (.env)
```env
APP_NAME="Crypto List"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Cache Configuration (using database for easy setup)
CACHE_STORE=database

# CoinGecko API (no key required for free tier)
COINGECKO_BASE_URL=https://api.coingecko.com/api/v3
COINGECKO_TIMEOUT=10
```

**Cache Strategy:**
- **Driver**: Database (SQLite) - no external dependencies required
- **Top 10 List**: 2 minutes TTL
- **Crypto Details**: 5 minutes TTL  
- **Search Results**: 10 minutes TTL
- **Benefit**: Prevents hitting CoinGecko's free tier rate limit (10-50 req/min)

### Frontend (.env)
```env
NUXT_PUBLIC_API_BASE_URL=http://127.0.0.1:8000
```

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **PHP Version**: 8.2+
- **Testing**: Pest/PHPUnit with Mockery
- **HTTP Client**: Guzzle (via Laravel HTTP)
- **API**: CoinGecko API v3 (free tier)

### Frontend
- **Framework**: Nuxt 4.2.1
- **Vue**: 3.5.25
- **TypeScript**: 5.x
- **Styling**: Tailwind CSS 3.x
- **SSR**: Enabled

---

## 📝 Code Quality Highlights

### Clean Code Principles
✅ **KISS** - Simple, no over-engineering  
✅ **DRY** - Reusable composables and utilities  
✅ **SOLID** - Service layer, dependency injection  
✅ **Type Safety** - Full TypeScript support  
✅ **Error Handling** - Comprehensive try-catch blocks  
✅ **Documentation** - PHPDoc comments throughout  

### Performance Optimizations
- **Retry Logic**: 3 attempts for resilience
- **Timeout**: 10 seconds to prevent hanging
- **Rate Limiting**: Protects backend from abuse
- **Debouncing**: 500ms search delay reduces API calls
- **SSR**: Faster initial page loads

---

## 📚 Additional Documentation

For detailed API documentation, see: `/backend/API_DOCUMENTATION.md`

---

## 👨‍💻 Development Notes

This project was built following best practices for a technical assessment:
- Clean, maintainable code structure
- Comprehensive testing strategy
- Proper error handling
- Type safety with TypeScript
- Responsive, modern UI
- Production-ready features (rate limiting, retry logic)

Time spent: ~4 hours (within assessment guidelines)

---

## 📄 License

MIT License - Free for personal and commercial use

---

## 🚀 Production Recommendations

This project is optimized for technical assessment. For production deployment, consider:

### Infrastructure
- **Cache**: Migrate from database to **Redis** for better performance and scalability
- **Queue**: Implement **Redis Queue** or **SQS** for background jobs
- **Database**: PostgreSQL or MySQL for production workloads
- **CDN**: CloudFlare or AWS CloudFront for static assets

### Features
- **Authentication**: JWT or Laravel Sanctum for user sessions
- **User Features**: Favorites, watchlists, price alerts
- **Real-time**: WebSocket integration for live price updates
- **Monitoring**: Sentry for error tracking, New Relic for APM
- **CI/CD**: GitHub Actions or GitLab CI for automated testing and deployment

### Security
- **Rate Limiting**: Per-user limits with API keys
- **HTTPS**: SSL/TLS certificates (Let's Encrypt)
- **CORS**: Restrict to specific domains
- **Input Validation**: Enhanced sanitization and validation rules

---

**Built with ❤️ for technical assessment**
