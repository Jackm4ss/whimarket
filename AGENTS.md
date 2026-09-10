# Repository Guidelines

## Project Overview

**WhiMarket** is a curated C2C marketplace platform tailored for authentic pre-loved fashion, apparel, and exclusive creator merchandise from Indonesian public figures, content creators, and verified sellers.

The application operates with three distinct user roles:
- **Buyer**: Discovery, search suggestions, wishlist, shopping cart, checkout with dynamic shipping and platform fees, manual bank transfer payment proof uploads, order tracking, and 48-hour delivery inspection with dispute mediation.
- **Seller (Creator)**: Invite-only onboarding using VIP Seller Access Codes, store profile (`/seller/@username`), product/variant catalog CRUD, order fulfillment (resi and packing proof), delivery claim requests, and dispute responses.
- **Admin**: Full operational control via Filament v5 at `/admin`—managing payments, orders, products, sellers, payouts, disputes, shipping rate zones, and dynamic platform fees.

---

## Architecture & Data Flow

WhiMarket is structured as a **Modular Monolith** on **Laravel 13** and **PHP 8.3/8.4**, with an administrative dashboard powered by **Filament v5** and **Livewire 4**, and a customer-facing storefront built using **Blade**, **Tailwind CSS v4** (CSS-first `@theme`), and **Alpine.js v3**.

### Core Data Flow & Pipelines

```
[Buyer: Cart] -> [CheckoutController::process] (pessimistic lock on variants)
                     |
                     v
             [Order Created] (Status: PendingPayment)
             - address_snapshot (JSON)
             - order_items (price_snapshot)
             - EscrowBalance created (held funds)
                     |
                     v
             [Payment Upload] (Status: PaymentVerification)
                     |
                     v
             [Admin Verifies Payment in Filament] -> (Status: Paid)
                     |
                     v
             [Seller Prepares & Fulfills Package] -> (Status: Shipped)
             - courier_name, tracking_number (resi)
             - pre_shipment_photo, receipt_photo
                     |
                     v
             [Delivery Confirmation] -> (Status: Delivered)
             - Fast Path: Buyer clicks "Barang Sudah Diterima"
             - Failsafe Path: Seller submits claim -> Admin verifies via tracking link
             - 48-hour inspection timer starts (inspection_deadline_at)
                     |
                     +---------------------------------------+
                     |                                       |
                     v (No Dispute / Confirmed)              v (Dispute Filed)
             [Order Completed]                       [Dispute Raised]
             - EscrowBalance released                - Escrow timer frozen
             - Payout generated (Status: PENDING)    - Admin mediates refund vs release
                     |
                     v
             [Admin Executes Bank Payout in Filament] -> (Status: Paid)
```

### Key Architectural Patterns

1. **State Machine Transitions (`spatie/laravel-model-states`)**:
   - `Order::$status` uses `OrderStatusState` (`PendingPayment` $\rightarrow$ `PaymentVerification` $\rightarrow$ `Paid` $\rightarrow$ `Processing` $\rightarrow$ `Shipped` $\rightarrow$ `Delivered` $\rightarrow$ `Completed`, or `Cancelled`/`Disputed`).
   - `Dispute::$status` uses `DisputeStatusState` (`OpenDispute` $\rightarrow$ `UnderAdminReview` $\rightarrow$ `ResolvedRefund` / `ResolvedRejected`).
   - State classes live in `app/States/Order/` and `app/States/Dispute/`. Never update status via raw strings; invoke `$order->status->transitionTo(Paid::class)`.
2. **Escrow Protection & Ledger**:
   - Held funds are tracked in `escrow_balances`. The held amount represents seller funds (`total_amount`), excluding shipping and admin fees.
   - Payout records (`payouts`) capture immutable snapshots of seller bank details (`bank_name`, `account_number`, `account_name`) upon completion.
3. **Pessimistic Inventory Locking**:
   - During checkout (`CheckoutController::process()`), variant IDs are sorted ascending before applying `ProductVariant::whereIn('id', $variantIds)->orderBy('id', 'asc')->lockForUpdate()` within `DB::transaction()` to prevent overselling and database deadlocks.
4. **Historical Immutability (Snapshots)**:
   - Orders preserve point-in-time facts: `address_snapshot` (JSON), `product_name_snapshot`, `variant_name_snapshot`, and `price_snapshot` in `order_items`. Never join live user addresses or current product prices for historical orders.
5. **Scheduled Escrow Automation**:
   - `AutoCompleteOrdersCommand` (`php artisan orders:auto-complete`) runs every 30 minutes in `routes/console.php` to transition orders past their 48-hour inspection deadline to `Completed` and generate pending payouts.

---

## Key Directories

```
app/
├── Console/Commands/        # Scheduled tasks (AutoCompleteOrdersCommand.php)
├── Enums/                   # Backed PHP enums (UserRole, PaymentStatus, PayoutStatus, etc.)
├── Filament/                # Admin panel configuration
│   ├── Pages/               # Admin pages (Dashboard, Login)
│   ├── Resources/           # Filament resources (Orders, Payments, Products, Sellers,
│   │                        #  Payouts, Disputes, ShippingZones, PlatformSettings, AccessCodes)
│   └── Widgets/             # Dashboard metrics and operational widgets
├── Http/Controllers/
│   ├── Api/                 # JSON API endpoints (RegionController)
│   ├── Auth/                # Authentication, Google OAuth (Socialite), PasswordController
│   ├── Buyer/               # Storefront: Catalog, Search, Cart, Checkout, Orders, Disputes
│   └── Seller/              # Merchant portal: Dashboard, Products CRUD, Fulfillment, Resi
├── Models/                  # Eloquent models with typed casts and relationship definitions
├── Policies/                # Authorization policies (OrderPolicy, ProductPolicy, DisputePolicy)
├── Providers/               # Service providers (AdminPanelProvider, AppServiceProvider)
├── Services/                # Domain services (ShippingRateService, PlatformFeeService)
├── States/                  # Spatie model states for Order and Dispute lifecycles
└── Support/                 # Static data fallbacks (MarketData.php)

resources/
├── css/app.css              # Tailwind CSS v4 CSS-first theme tokens & custom utilities
├── js/app.js                # Frontend bootstrapping (Alpine.js)
└── views/
    ├── components/          # Reusable Blade UI components (cards, badges, modals, selectors)
    ├── layouts/             # App layouts (app.blade.php, navbar.blade.php, footer.blade.php)
    ├── checkout/            # Checkout form (index.blade.php) and payment instructions (payment.blade.php)
    ├── orders/              # Buyer order listing and detail views
    ├── seller/              # Seller dashboard, catalog management, and order fulfillment
    └── filament/modals/     # Custom admin modals (order-details.blade.php, payment-details.blade.php)

database/
├── factories/               # Model factories with custom roles/states
├── migrations/              # Database schema migrations
└── seeders/                 # Seeders (DatabaseSeeder, ShippingZoneSeeder, PlatformSettingSeeder)

tests/
├── Feature/                 # Concurrency, full order lifecycle, auth, dispute, fee tests
└── TestCase.php             # Base test configuration (whimarket_test connection, CSRF disabled)
```

---

## Development Commands

### Running Locally
```bash
# Start PHP development server (default port 8000)
php artisan serve

# Start Vite dev server for hot reloading (using Bun)
bun run dev

# Run queue worker (if running background jobs)
php artisan queue:work

# Run scheduled commands manually
php artisan orders:auto-complete
```

### Build & Asset Compilation
```bash
# Build frontend assets for production
bun run build

# Optimize and compress asset images via Sharp
bun run compress
```

### Testing & Code Style
```bash
# Run full PHPUnit test suite (compact mode)
php artisan test --compact

# Run a specific test file or filter by method name
php artisan test tests/Feature/OrderLifecycleTest.php
php artisan test --filter=test_zero_overselling_under_concurrent_stock_reduction

# Format modified PHP files according to project Pint rules
php vendor/bin/pint --dirty --format agent

# Format all project files
php vendor/bin/pint --format agent
```

### Database & Seeding
```bash
# Run migrations
php artisan migrate

# Seed fresh database with demo accounts and reference data
php artisan db:seed

# Inspect routes or database config
php artisan route:list --path=admin
php artisan config:show database.default
```

---

## Code Conventions & Common Patterns

### Formatting & PHP Standards
- **PHP 8 Constructor Promotion**: Use `public function __construct(public GitHub $github) {}`. Avoid empty parameterless constructors.
- **Strict Typing & Return Types**: Declare explicit return types and method parameter hints on all controller methods, service methods, and models:
  ```php
  public function calculate(string|int|null $provinceNameOrCode): array
  ```
- **Control Structures**: Always use curly braces for control structures, even for single-line bodies.
- **Enums**: Use PascalCase / TitleCase for enum cases: `UserRole::ADMIN`, `PaymentStatus::PENDING_REVIEW`.

### Controller & Validation Conventions
- **Inline Validation**: Controllers use inline `$request->validate([...])` with Indonesian validation messages. FormRequest classes are not used in this project.
- **Authorization**: Enforce user authorization using Laravel policies: `Gate::authorize('view', $order)` or `$this->authorize(...)`.
- **Image Upload Security**: Enforce binary image verification using `@getimagesize()` on transfer proof and receipt uploads to confirm valid image headers (JPEG, PNG, WEBP) rather than trusting mime extensions alone.

### Database, Transactions & Caching
- **Transaction Safety**: All multi-table updates (checkout, order fulfillment, dispute resolution, payment approval) must be wrapped inside `DB::transaction(function () { ... })`.
- **Deadlock Prevention**: Always sort model IDs in ascending order before applying `->lockForUpdate()`.
- **Caching Strategy**:
  - Semi-static data (e.g. `ShippingZone`, `PlatformSetting`, `RegionController` lookups) must be cached with 24-hour expiration (`now()->addHours(24)`).
  - Always register cache invalidation in Eloquent model lifecycle hooks:
    ```php
    protected static function booted(): void
    {
        static::saved(fn ($setting) => Cache::forget("platform_setting_{$setting->key}"));
        static::deleted(fn ($setting) => Cache::forget("platform_setting_{$setting->key}"));
    }
    ```
- **Default Table Sorting**: All Filament resource tables must specify `->defaultSort('created_at', 'desc')` to display the newest records first.

---

## Important Files

| Path | Purpose |
| :--- | :--- |
| `routes/web.php` | Main web routes for Buyer, Seller, Authentication, and Regional APIs |
| `routes/console.php` | Scheduled commands (including 30-minute auto-completion of orders) |
| `app/Providers/Filament/AdminPanelProvider.php` | Filament panel bootstrap, navigation groups, theme colors, and custom CSS |
| `app/Models/Order.php` | Master order model with Spatie state machine cast and snapshot fields |
| `app/States/Order/OrderStatusState.php` | Finite state machine transitions for the complete order lifecycle |
| `app/Http/Controllers/Buyer/CheckoutController.php` | Concurrency-locked cart checkout, shipping rate, and order creation |
| `app/Services/ShippingRateService.php` | Indonesian 5-zone logistics calculation with 24-hour caching |
| `app/Services/PlatformFeeService.php` | Dynamic platform admin fee calculation service |
| `app/Models/PlatformSetting.php` | Dynamic key-value configuration model with auto-invalidating cache |
| `resources/css/app.css` | Tailwind CSS v4 design tokens (`--color-primary-purple: #4F26A6`, etc.) |
| `database/seeders/DatabaseSeeder.php` | Master database seeder for demo accounts, shipping zones, and admin fees |

---

## Runtime & Tooling Preferences

- **PHP Version**: Requires `^8.3` (tested and running on PHP 8.4).
- **Node / JS Package Manager**: **Bun** is the primary package manager (`bun.lock`). Use `bun run build`, `bun run dev`, and `bun add`. Avoid committing `package-lock.json` or `pnpm-lock.yaml`.
- **Database Engine**: Defaults to SQLite for local development (`database/database.sqlite`); utilizes MariaDB (`whimarket_test` on `127.0.0.1:3306`) for testing and production environments.
- **Git Push Policy**: **NEVER** push commits to remote (`git push origin dev` or any remote) without explicit instruction from the user. Always keep commits local until specifically commanded.
- **Local Dev Fast-Login**: Quick authentication bypass routes exist for local development:
  - Admin: `/auth/dev-login/admin`
  - Seller: `/auth/dev-login/seller`
  - Buyer: `/auth/dev-login/buyer`

---

## Testing & QA

### Framework & Configuration
- **Test Runner**: **PHPUnit 12+** (`phpunit.xml`) executed via `php artisan test`.
- **Testing Database**: Configured to run against `whimarket_test` with `DatabaseMigrations` trait applied across feature tests.
- **Drivers**: Array drivers are used during tests for `CACHE_STORE=array`, `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync`, and `BCRYPT_ROUNDS=4` for fast execution.

### Key Test Suites
- `tests/Feature/OrderLifecycleTest.php`: Complete end-to-end flow from checkout to payment verification, shipment, buyer confirmation, and payout release.
- `tests/Feature/CheckoutConcurrencyTest.php`: Concurrency test proving zero-overselling under race conditions when variant stock is 1.
- `tests/Feature/PlatformAdminFeeTest.php`: Dynamic fee calculations, payment parity, and disabled fee scenarios.
- `tests/Feature/SellerAccessCodeTest.php`: VIP access code validation, quota locking, and admin resets.
- `tests/Feature/DisputeResolutionTest.php`: Dispute submission with unboxing proof, counter-evidence, and mediation outcomes.

### Testing Rules for AI Agents
1. **Always use Model Factories**: Leverage `User::factory()->buyer()`, `User::factory()->admin()`, `ProductVariant::factory()`, etc.
2. **Assign Spatie Roles in Setup**: Ensure Spatie roles are created and assigned:
   ```php
   Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
   $user->assignRole('buyer');
   ```
3. **No Padding Tests**: Write tests that defend observable contracts, boundaries, and invariants. Do not write tests for trivial setters or framework wiring.
4. **Always verify Pint and Tests**: Run `vendor/bin/pint --dirty --format agent` and `php artisan test` before completing any code changes.
