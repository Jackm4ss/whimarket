# Repository Guidelines

## Project Overview

**WhiMarket** is a curated C2C marketplace platform engineered for authentic pre-loved fashion, apparel, and exclusive creator merchandise from Indonesian public figures, content creators, and verified sellers.

The application operates across three distinct actor roles:
- **Buyer**: Catalog discovery, AJAX search suggestions (`/api/search-suggest`), wishlist, shopping cart item selection/batch management, checkout with Indonesian regional zone shipping and dynamic platform fees, manual bank transfer payment proof uploads (deep binary inspected), order tracking, and 48-hour delivery inspection with dispute mediation.
- **Seller (Creator)**: Invite-only onboarding using VIP Seller Access Codes, public store profile (`/seller/@username`), product and variant catalog CRUD, order fulfillment (tracking number and packing proof photos), delivery claim requests, and dispute counter-evidence responses.
- **Admin**: Operational control via Filament v5 at `/admin`—managing payments, orders, catalog moderation, seller account lifecycles, escrow payout disbursement, dispute mediation, shipping rate zones, platform fee settings, and VIP access codes.

---

## Architecture & Data Flow

WhiMarket is engineered as a **Modular Monolith** on **Laravel 13** and **PHP 8.3/8.4**, combining an administrative backoffice powered by **Filament v5** and **Livewire 4** with a customer-facing storefront built using **Blade**, **Tailwind CSS v4** (CSS-first `@theme` design tokens in `resources/css/app.css`), and **Alpine.js v3**.

### Core Data Flow & Pipelines

```
[Buyer: Cart] -> [CheckoutController::process] (Pessimistic lock on variants: lockForUpdate)
                     |
                     v
             [Order Created] (Status: PendingPayment)
             - address_snapshot (JSON)
             - order_items (price_snapshot, product_name_snapshot, variant_name_snapshot)
             - EscrowBalance created (held funds: total_amount excluding shipping/fees)
                     |
                     v
             [Payment Upload] (Status: PaymentVerification)
             - Deep binary image inspection (@getimagesize)
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

### Finite State Machines (`spatie/laravel-model-states`)

#### 1. Order State Graph (`app/States/Order/OrderStatusState.php`)
```
PendingPayment ──► PaymentVerification ──► Paid ──► Processing ──► Shipped ──► Delivered ──► Completed
      │                    │                                               ▲              ▲
      │                    ├──────────────► PendingPayment                 │              │
      ▼                    ▼                                               │              │
  Cancelled            Cancelled                                      Disputed ───────────┤
                                                                           │
                                                                           ▼
                                                                       Cancelled
```
- Never update order status via raw strings. Always invoke state transitions: `$order->status->transitionTo(Paid::class)`.
- Assert state in tests using state classes: `$this->assertTrue($order->status->equals(Paid::class))`.

#### 2. Dispute State Graph (`app/States/Dispute/DisputeStatusState.php`)
```
OpenDispute ──► SellerResponded ──► UnderAdminReview ──► ResolvedRefund (Order -> Cancelled)
     │                 │                     │
     └─────────────────┴─────────────────────┴─────────► ResolvedRejected (Order -> Completed)
```
- Filing a dispute requires buyer description, photos, and an unboxing video (`video_unboxing`).
- Disputing freezes the 48-hour auto-completion countdown.
- Admin resolving refund cancels the order and retains escrow for manual reversal; rejecting the dispute completes the order, releases escrow, and generates seller payout.

### Key Architectural Patterns

1. **Pessimistic Inventory Locking & Deadlock Prevention**:
   - During checkout (`CheckoutController::process()`), variant IDs are strictly sorted in ascending order before locking within `DB::transaction()`:
     ```php
     $variantIds = $items->pluck('product_variant_id')->sort()->values()->all();
     $lockedVariants = ProductVariant::whereIn('id', $variantIds)
         ->orderBy('id', 'asc')
         ->lockForUpdate()
         ->get()
         ->keyBy('id');
     ```
   - Prevents database deadlocks and guarantees zero overselling under race conditions.
2. **Historical Immutability (Snapshots)**:
   - Orders capture point-in-time facts: `address_snapshot` (JSON) on `orders`, plus `product_name_snapshot`, `variant_name_snapshot`, and `price_snapshot` on `order_items`.
   - Payouts store `bank_details_snapshot` (`bank_name`, `account_number`, `account_name`) taken directly from the seller profile at completion time.
   - Never join live user addresses or live variant prices for historical order records.
3. **Escrow Protection & Ledger**:
   - `escrow_balances` tracks seller product funds (`total_amount`), explicitly excluding shipping costs and platform admin fees.
   - Funds remain locked (`is_released = false`) until the buyer confirms delivery, the 48-hour inspection deadline elapses, or an admin rejects a dispute.
4. **Scheduled Escrow Automation**:
   - `AutoCompleteOrdersCommand` (`php artisan orders:auto-complete`) runs every 30 minutes in `routes/console.php`.
   - Chunks delivered orders where `inspection_deadline_at <= now()`, skips disputed orders, transitions status to `Completed`, releases escrow balance, and creates pending `Payout` records.
5. **Buyer Follow System (`seller_followers`)**:
   - Many-to-many relationship between `User` and `Seller` via `SellerFollower` pivot.
   - Guarded against self-following (`$user->id === $seller->user_id` returns HTTP 422). Supports dual JSON and Blade redirects.
6. **Seller Account Lifecycle & Store Status**:
   - `SellerStatus` enum: `PENDING`, `VERIFIED`, `REJECTED`, `SUSPENDED`.
   - Suspended sellers have products automatically hidden from public browse/search, cart items deselected and rejected, and checkout blocked.
7. **Deep Binary Upload Security**:
   - Transfer proofs and fulfillment photos are inspected using `@getimagesize()` on the server to verify binary image headers (`IMAGETYPE_JPEG`, `IMAGETYPE_PNG`, `IMAGETYPE_WEBP`), preventing client-side MIME spoofing.

---

## Key Directories

| Directory | Purpose |
|:---|:---|
| `app/Console/Commands/` | Scheduled tasks (e.g. `AutoCompleteOrdersCommand.php` for 48h escrow settlement) |
| `app/Enums/` | Backed string enums (`UserRole`, `SellerStatus`, `PaymentStatus`, `PayoutStatus`, `ProductStatus`, `ProductCondition`) |
| `app/Filament/` | Filament v5 admin panel bootstrap, pages, resources (`Orders`, `Payments`, `Payouts`, `Disputes`, `Sellers`, `Products`, `ShippingZones`, `PlatformSettings`, `AccessCodes`), and widgets |
| `app/Http/Controllers/Api/` | Public JSON APIs (e.g. `RegionController` for Creasi Nusa Indonesian administrative lookups) |
| `app/Http/Controllers/Auth/` | Authentication, Google OAuth (`GoogleAuthController`), password management, and fast-login dev bypasses |
| `app/Http/Controllers/Buyer/` | Buyer storefront: `CatalogController`, `SearchController`, `CartController`, `CheckoutController`, `OrderController`, `DisputeController`, `FollowController` |
| `app/Http/Controllers/Seller/` | Creator portal: `SellerPortalController` (dashboard, fulfillment, dispute response), `SellerProductController` (product/variant CRUD) |
| `app/Models/` | Eloquent models with typed casts, relationship definitions, and booted lifecycle hooks |
| `app/Policies/` | Authorization policies (`OrderPolicy`, `ProductPolicy`, `DisputePolicy`, `SellerPolicy`) |
| `app/Providers/` | Service providers (`AppServiceProvider`, `AdminPanelProvider`) |
| `app/Services/` | Domain services (`ShippingRateService` 5-zone logistics, `PlatformFeeService` dynamic platform fees) |
| `app/States/` | Spatie Model State definitions for `Order/` and `Dispute/` lifecycles |
| `app/Support/` | Static reference data and mock fallbacks (`MarketData.php`) |
| `resources/css/` | Tailwind CSS v4 CSS-first design tokens (`app.css` with `@theme`, brand color tokens, custom utilities) |
| `resources/js/` | Frontend bootstrapping (`app.js` with Alpine.js v3) |
| `resources/views/` | Blade templates: `layouts/`, `components/`, `buyer/`, `seller/`, `checkout/`, `orders/`, `filament/modals/` |
| `database/factories/` | Model factories with role and state helpers (`UserFactory`, `OrderFactory`, `ProductVariantFactory`) |
| `database/migrations/` | Database schema migrations with MariaDB-compatible foreign keys and indexes |
| `database/seeders/` | Reference seeders (`DatabaseSeeder`, `AdminSeeder`, `CategorySeeder`) |
| `scripts/` | Build scripts (`compress-images.js` Sharp optimizer for `public/assets`) |
| `tests/Feature/` | Integration and unit feature test suites executed on MariaDB (`whimarket_test`) |

---

## Development Commands

### Local Development Servers
```bash
# Start PHP development server (default port 8000)
php artisan serve

# Start Vite dev server for hot module replacement (via Bun)
bun run dev

# Run background queue worker for asynchronous jobs
php artisan queue:work

# Manually trigger 48-hour escrow auto-completion
php artisan orders:auto-complete
```

### Local Dev Fast-Login Bypasses
Fast-login endpoints exist in `GoogleAuthController@devLogin` (automatically blocked with HTTP 404 in production):
- **Admin**: `http://127.0.0.1:8000/auth/dev-login/admin` (`admin@whimarket.com`)
- **Seller (Creator)**: `http://127.0.0.1:8000/auth/dev-login/seller` (`celloszx@whimarket.com`)
- **Creator (Alternative)**: `http://127.0.0.1:8000/auth/dev-login/bintang` (`bintang.creator@gmail.com`)
- **Buyer**: `http://127.0.0.1:8000/auth/dev-login/buyer` (`buyer@whimarket.com`)
- **Fresh Buyer (Onboarding Modal Test)**: `http://127.0.0.1:8000/auth/dev-login/fresh` (`rina.melati@example.com`)

### Build & Asset Compilation
```bash
# Full frontend production build (runs Sharp asset compression then Vite build via Bun)
bun run build

# Standalone recursive asset image compression using Sharp
bun run compress
```

### Testing & Code Formatting
```bash
# Run full PHPUnit test suite (compact mode)
php artisan test --compact

# Run full test suite via Composer (clears configuration cache first)
composer test

# Run a specific test file or filter by method
php artisan test tests/Feature/OrderLifecycleTest.php
php artisan test tests/Feature/CheckoutConcurrencyTest.php
php artisan test --filter=test_zero_overselling_under_concurrent_stock_reduction

# Format modified PHP files using Laravel Pint rules
vendor/bin/pint --dirty --format agent

# Format all PHP files in repository
vendor/bin/pint --format agent
```

### Database & Migrations
```bash
# Run pending migrations
php artisan migrate

# Seed database with roles, admin account, and storefront categories
php artisan db:seed

# Inspect routes or database configuration
php artisan route:list --path=admin
php artisan config:show database.default
```

---

## Code Conventions & Common Patterns

### Strict Typing & Method Signatures
- **Explicit Parameter & Return Types**: Declare strict return types and parameter type hints on all controller actions, service methods, and Eloquent relationships:
  ```php
  public function calculate(string|int|null $provinceNameOrCode): array
  public function followedSellers(): BelongsToMany
  public function isFollowing(Seller|int $seller): bool
  ```
- **PHP 8 Constructor Promotion**: Prefer `public function __construct(public ShippingRateService $shippingService) {}`. Avoid empty parameterless constructors.
- **Control Structures**: Always use curly braces for control structures, even for single-line bodies.
- **Enums**: Backed string enums with PascalCase naming and Indonesian user-facing `label()` methods:
  ```php
  enum SellerStatus: string {
      case PENDING = 'pending';
      case VERIFIED = 'verified';
      case REJECTED = 'rejected';
      case SUSPENDED = 'suspended';

      public function label(): string {
          return match ($this) {
              self::PENDING => 'Menunggu Verifikasi',
              self::VERIFIED => 'Terverifikasi',
              self::REJECTED => 'Ditolak',
              self::SUSPENDED => 'Ditangguhkan',
          };
      }
  }
  ```

### Controller & Validation Conventions
- **Inline Validation**: Controllers strictly use inline `$request->validate([...], [...])` with Indonesian validation messages. FormRequest classes are not used in this project.
- **Dual Response Handling**: Controller actions supporting both Alpine AJAX calls and standard Blade submissions must inspect `$request->wantsJson()`, `$request->expectsJson()`, or `$request->ajax()`:
  ```php
  if ($request->wantsJson() || $request->expectsJson() || $request->ajax()) {
      return response()->json([
          'success' => true,
          'message' => 'Berhasil ditambahkan ke keranjang!',
      ]);
  }
  return redirect()->back()->with('success', 'Berhasil ditambahkan ke keranjang!');
  ```
- **Authorization**: Enforce access control via Laravel policies (`$this->authorize(...)` or `Gate::authorize(...)`).
- **Phone Number Normalization**: Indonesian phone numbers must always be normalized to E.164 (`+628...`) format before persistence.

### Database, Transactions & Caching
- **Transaction Boundaries**: All multi-table updates (checkout, order fulfillment, dispute resolution, payment approval) must be wrapped inside `DB::transaction(function () { ... })`.
- **Ascending ID Sort for Locks**: Always sort model IDs in ascending order prior to calling `->lockForUpdate()`.
- **Caching Strategy**:
  - Semi-static data (`ShippingZone`, `PlatformSetting`, regional lookups) must be cached with 24-hour expiration (`now()->addHours(24)`).
  - Register automatic cache invalidation in Eloquent model `booted()` lifecycle hooks (`saved` and `deleted`).
- **Filament Table Sorting**: All Filament resource tables must define `->defaultSort('created_at', 'desc')` to display newest records first.

---

## Important Files

| Path | Purpose |
|:---|:---|
| `routes/web.php` | Main web routes for Buyer storefront, Seller portal, Auth, and Regional APIs |
| `routes/console.php` | Scheduled commands (including 30-minute auto-completion of delivered orders) |
| `app/Providers/Filament/AdminPanelProvider.php` | Filament panel bootstrap, navigation groups, theme colors, and custom CSS |
| `app/Models/Order.php` | Master order model with Spatie state machine cast and historical snapshot fields |
| `app/States/Order/OrderStatusState.php` | Finite state machine transitions for the complete order lifecycle |
| `app/States/Dispute/DisputeStatusState.php` | Finite state machine transitions for dispute mediation |
| `app/Http/Controllers/Buyer/CheckoutController.php` | Concurrency-locked cart checkout, shipping rate calculation, and order creation |
| `app/Http/Controllers/Buyer/CartController.php` | Cart item selection, quantity updates, batch select-all, and inactive product guards |
| `app/Http/Controllers/Buyer/FollowController.php` | Followed stores directory and live AJAX follow/unfollow toggle |
| `app/Services/ShippingRateService.php` | Indonesian 5-zone logistics calculation with 24-hour caching |
| `app/Services/PlatformFeeService.php` | Dynamic platform admin fee calculation service |
| `app/Models/PlatformSetting.php` | Dynamic key-value configuration model with auto-invalidating cache |
| `resources/css/app.css` | Tailwind CSS v4 CSS-first design tokens (`--color-primary-purple: #4F26A6`, etc.) |
| `scripts/compress-images.js` | Sharp image optimization script recursively processing `public/assets` |
| `database/seeders/DatabaseSeeder.php` | Master database seeder invoking AdminSeeder & CategorySeeder |
| `database/seeders/AdminSeeder.php` | Seeds Spatie roles (`admin`, `seller`, `buyer`) and default admin account |
| `database/seeders/CategorySeeder.php` | Seeds active storefront categories |

---

## Runtime & Tooling Preferences

- **PHP Runtime**: Requires `^8.3` (tested and running on PHP 8.4).
- **Node / JS Package Manager**: **Bun** is strictly the package manager (`bun.lock` committed). Use `bun run build`, `bun run dev`, `bun run compress`, and `bun add`. Never commit `package-lock.json` or `pnpm-lock.yaml`.
- **Database Engine**:
  - Local development defaults to SQLite (`database/database.sqlite`) or MariaDB.
  - Testing and production environments strictly use MariaDB (`whimarket_test` on `127.0.0.1:3306`) to support pessimistic row locking (`lockForUpdate`).
- **Git Push Policy**: **NEVER** push commits to remote repositories (`git push origin dev` or any remote) without explicit instruction from the user. Keep commits local.
- **Visual Verification Protocol**: All UI modifications to Blade templates, Tailwind CSS, or Alpine components must be visually verified across both **Desktop (1440px)** and **Mobile (375px)** viewports using Puppeteer browser automation and OMP vision inspection (`read("<path>?q=<query>")`). Never assume correctness from HTTP 200 or clean console logs alone.

---

## Testing & QA

### Framework & Configuration
- **Test Runner**: **PHPUnit 12+** (`phpunit.xml`) executed via `php artisan test`.
- **Testing Database**: Configured to run against `whimarket_test` on MariaDB (`127.0.0.1:3306`) to accurately replicate pessimistic locking behavior.
- **Drivers**: Array and sync drivers configured in `phpunit.xml`: `CACHE_STORE=array`, `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync`, `MAIL_MAILER=array`.
- **Global CSRF Bypass**: `tests/TestCase.php` disables `PreventRequestForgery` across all HTTP tests.

### Database Lifecycle Strategies
1. **`DatabaseMigrations`**: Applied for multi-step transaction, concurrency, and state machine tests (`OrderLifecycleTest`, `CheckoutConcurrencyTest`, `DisputeResolutionTest`, `PlatformAdminFeeTest`).
2. **`RefreshDatabase`**: Applied for standard CRUD, boundary, and access control feature tests (`SellerFollowTest`, `SellerAccessCodeTest`, `InactiveProductPurchaseTest`, `SellerStoreStatusTest`, `ProfileSettingsTest`).

### Key Feature Test Suites

| Test Suite | Lifecycle | Scope |
|:---|:---|:---|
| `tests/Feature/OrderLifecycleTest.php` | `DatabaseMigrations` | End-to-end flow: checkout, payment proof, admin verification, seller fulfillment, buyer delivery confirmation, completion, and escrow/payout release |
| `tests/Feature/CheckoutConcurrencyTest.php` | `DatabaseMigrations` | Concurrency race condition proving zero-overselling when variant stock is 1 |
| `tests/Feature/PlatformAdminFeeTest.php` | `DatabaseMigrations` | Dynamic platform fee calculations, active/inactive toggle, and payment parity |
| `tests/Feature/SellerAccessCodeTest.php` | `RefreshDatabase` | VIP access code validation, usage quota locking, one-time code expiration, and admin resets |
| `tests/Feature/DisputeResolutionTest.php` | `DatabaseMigrations` | Dispute submission with unboxing video and photo proof, seller counter-evidence, and admin mediation |
| `tests/Feature/SellerFollowTest.php` | `RefreshDatabase` | Store follow/unfollow toggle, unauthenticated guest rejection, self-follow guard, and followed stores page |
| `tests/Feature/InactiveProductPurchaseTest.php` | `RefreshDatabase` | Security boundaries preventing purchase, cart addition, and checkout of inactive catalog items |
| `tests/Feature/SellerStoreStatusTest.php` | `RefreshDatabase` | Store status transitions (VERIFIED, PENDING, SUSPENDED) and public catalog visibility rules |
| `tests/Feature/ProfileSettingsTest.php` | `RefreshDatabase` | User profile updates, Indonesian phone E.164 normalization, and address CRUD |

### Testing Rules for AI Agents
1. **Always use Model Factories**: Leverage `User::factory()->buyer()`, `User::factory()->seller()`, `ProductVariant::factory()`, etc.
2. **Assign Spatie Roles in Setup**: Ensure Spatie roles are initialized in `setUp()` when using `DatabaseMigrations`:
   ```php
   Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
   $user->assignRole('buyer');
   ```
3. **State Machine Assertions**: Assert Spatie Model States using the state object contract:
   ```php
   $this->assertTrue($order->status->equals(Paid::class));
   ```
4. **Mock File Uploads**: Use `Storage::fake('public')` and `UploadedFile::fake()->create(...)`.
5. **No Padding Tests**: Write tests that defend observable contracts, boundaries, and invariants. Do not test framework wiring or incidental strings.
6. **Always Verify Pint and Tests**: Run `vendor/bin/pint --dirty --format agent` and `php artisan test --compact` before completing any task.
