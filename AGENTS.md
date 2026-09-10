# Repository Guidelines

## Project Overview

**WhiMarket** is a curated Indonesian C2C marketplace platform for authentic pre-loved fashion, apparel, and exclusive creator merchandise from public figures, content creators, and verified sellers.

The application serves three distinct user roles:
- **Buyer**: Storefront catalog discovery, live autocomplete suggestions (`/api/search-suggest`), wishlist, cart, checkout with dynamic regional shipping rates and platform admin fees, manual bank transfer payment slip uploads, order tracking, 48-hour delivery inspection with dispute mediation, and multi-photo/video product reviews.
- **Seller (Creator)**: Invite-only onboarding using VIP Seller Access Codes (`/seller/register`), public store profile (`/seller/{username}`), catalog & variant CRUD, order fulfillment (tracking number, pre-shipment photo, receipt photo), delivery claims, and dispute counter-evidence rebuttals.
- **Admin**: Full backoffice control via Filament v5 at `/admin`—payment verification (approval or rejection with stock restoration), orders, catalog moderation, sellers, payouts, dispute mediation, shipping rate zones, platform settings, and VIP access codes.

---

## Architecture & Data Flow

WhiMarket is structured as a **Modular Monolith** on **Laravel 13** and **PHP 8.4+**, combining an administrative backoffice powered by **Filament v5** and **Livewire 4** with a customer-facing storefront built using **Blade**, **Tailwind CSS v4** (CSS-first `@theme` design tokens in `resources/css/app.css`), and **Alpine.js v3**.

### Core Data Flow & Pipelines

```
[Buyer: Cart] -> [CheckoutController::process] (pessimistic lock on variants)
                     |
                     v
             [Order Created] (Status: PendingPayment)
             - address_snapshot (JSON)
             - order_items (price_snapshot, product_name_snapshot, variant_name_snapshot)
             - EscrowBalance created (held funds: total_amount)
                     |
                     v
             [Payment Upload] (Status: PaymentVerification)
             - Binary magic byte validation (@getimagesize)
                     |
                     +---------------------------------------+
                     | (Approve)                             | (Reject)
                     v                                       v
        [Admin Verifies in Filament]              [Admin Rejection Options]
        - Status: Paid                            - Option A: Cancel & restore stock
                     |                            - Option B: Return to PendingPayment for re-upload
                     v
        [Seller Prepares & Fulfills Package] -> (Status: Shipped)
        - courier_name, tracking_number (resi)
        - pre_shipment_photo, receipt_photo
                     |
                     v
        [Delivery Confirmation] -> (Status: Delivered)
        - Fast Path: Buyer clicks "Barang Sudah Diterima"
        - Failsafe Path: Seller submits claim -> Admin verifies via courier tracking link
        - 48-hour inspection timer starts (inspection_deadline_at)
                     |
                     +---------------------------------------+
                     |                                       |
                     v (No Dispute / Confirmed)              v (Dispute Filed)
             [Order Completed]                       [Dispute Raised]
             - EscrowBalance released                - Escrow countdown timer frozen
             - Payout generated (Status: PENDING)    - Seller submits rebuttal & photos
             - Fast Path: Buyer confirms             - Admin mediates refund vs release
             - Auto Path: AutoCompleteOrdersCommand          |
                     |                                       +-----------------------+
                     v                                       | (Refund)              | (Reject)
        [Admin Executes Bank Payout in Filament]             v                       v
        - Upload transfer proof screenshot             [Order Cancelled]       [Order Completed]
        - Status: Paid                                 - Stock restored        - Escrow released
                                                       - Buyer refunded        - Payout generated
```

### Key Architectural Patterns

1. **State Machine Transitions (`spatie/laravel-model-states`)**:
   - `Order::$status` uses `OrderStatusState`:
     - `PendingPayment` $\rightarrow$ `PaymentVerification` | `Cancelled`
     - `PaymentVerification` $\rightarrow$ `Paid` | `PendingPayment` (re-upload) | `Cancelled` (reject & restore)
     - `Paid` $\rightarrow$ `Processing` $\rightarrow$ `Shipped`
     - `Shipped` $\rightarrow$ `Delivered`
     - `Delivered` $\rightarrow$ `Completed` | `Disputed`
     - `Disputed` $\rightarrow$ `Completed` (dispute rejected) | `Cancelled` (dispute approved/refunded)
   - `Dispute::$status` uses `DisputeStatusState`:
     - `OpenDispute` $\rightarrow$ `SellerResponded` $\rightarrow$ `UnderAdminReview` $\rightarrow$ `ResolvedRefund` | `ResolvedRejected`
   - State classes live in `app/States/Order/` and `app/States/Dispute/`. Never update status via raw strings; invoke `$order->status->transitionTo(Paid::class)`.
2. **Escrow Protection & Ledger**:
   - Held funds are tracked in `escrow_balances`. Held amount represents seller goods total (`total_amount`), excluding shipping costs and platform admin fees.
   - Payout records (`payouts`) capture immutable snapshots of seller bank details (`bank_name`, `account_number`, `account_name`) upon order completion.
   - Admin disburses payouts by uploading proof of transfer in `/admin/payouts`.
3. **Pessimistic Inventory Locking**:
   - In `CheckoutController::process()`, variant IDs are sorted ascending before applying row-level locks within `DB::transaction()` to prevent overselling and database deadlocks:
     ```php
     $variantIds = $items->pluck('product_variant_id')->sort()->values()->all();
     $lockedVariants = ProductVariant::whereIn('id', $variantIds)
         ->orderBy('id', 'asc')
         ->lockForUpdate()
         ->get()
         ->keyBy('id');
     ```
4. **Historical Immutability (Snapshots)**:
   - Orders preserve point-in-time facts: `address_snapshot` (JSON) on `Order`, plus `product_name_snapshot`, `variant_name_snapshot`, and `price_snapshot` in `order_items`. Never join live user addresses or current product prices for historical orders.
5. **Scheduled Escrow Automation**:
   - `AutoCompleteOrdersCommand` (`php artisan orders:auto-complete`) runs every 30 minutes in `routes/console.php` to transition delivered orders past their 48-hour inspection deadline to `Completed`, release escrow balances, and generate pending payouts.
6. **Buyer Follow System (`seller_followers`)**:
   - Many-to-many relationship linking `User` and `Seller` via `SellerFollower` pivot.
   - Managed by `FollowController`: includes live AJAX follow/unfollow toggle, self-follow guard (HTTP 422), dynamic follower count formatting (`12,4rb`), and a dedicated management view at `/toko-diikuti`.
7. **Seller Account Lifecycle & VIP Onboarding**:
   - `SellerStatus` enum: `PENDING`, `VERIFIED`, `REJECTED`, `SUSPENDED`.
   - Onboarding requires VIP Seller Access Codes (`seller_access_codes`), validated and redeemed at `/seller/register`.
   - Administrators toggle verification or suspend stores in `/admin/sellers`. Suspended stores trigger warning banners in the seller portal, hide products from the public catalog, automatically deselect cart items, and block new checkouts.
8. **Product Review & Rating Subsystem**:
   - Reviews require completed orders (`$order->status->equals(Completed::class)`), enforced per line item (`order_item_id` unique index).
   - Supports 1-5 star ratings, optional comments, up to 5 photos (5MB max each, instant thumbnail preview and deletion), and unboxing videos (50MB max, HTML5 player preview).
   - Real ratings and counts are aggregated on `Product` and `Seller` models with fallback to demo figures for unreviewed demo creators.

---

## Key Directories

```
app/
├── Console/Commands/        # Scheduled tasks (AutoCompleteOrdersCommand.php)
├── Enums/                   # Backed PHP enums (UserRole, PaymentStatus, PayoutStatus, SellerStatus, ProductStatus, ProductCondition)
├── Filament/                # Filament v5 admin panel configuration
│   ├── Pages/               # Admin pages (Dashboard, Login)
│   ├── Resources/           # Filament resources (Orders, Payments, Products, Sellers,
│   │                        #  Payouts, Disputes, ShippingZones, PlatformSettings, AccessCodes)
│   └── Widgets/             # Dashboard metrics and operational widgets
├── Http/Controllers/
│   ├── Api/                 # JSON endpoints (RegionController for Nusa regional divisions)
│   ├── Auth/                # Authentication, Google OAuth (Socialite), dev-login bypasses
│   ├── Buyer/               # Storefront: Catalog, Search, Cart, Checkout, Orders, Disputes, Follows, Reviews
│   └── Seller/              # Merchant portal: Dashboard, Products CRUD, Fulfillment, Resi, PayoutAccount
├── Models/                  # Eloquent models with typed casts and relationship definitions
├── Policies/                # Authorization policies (OrderPolicy, ProductPolicy, DisputePolicy, SellerPolicy)
├── Providers/               # Service providers (AdminPanelProvider, AppServiceProvider)
├── Services/                # Domain services (ShippingRateService, PlatformFeeService)
├── States/                  # Spatie model states for Order and Dispute lifecycles
└── Support/                 # Static data fallbacks (MarketData.php)

resources/
├── css/app.css              # Tailwind CSS v4 CSS-first theme tokens (@theme) & custom utilities
├── js/app.js                # Frontend bootstrapping (Alpine.js v3)
└── views/
    ├── components/          # Reusable Blade UI components (cards, badges, modals, selectors)
    ├── layouts/             # App layouts (app.blade.php, navbar.blade.php, footer.blade.php)
    ├── checkout/            # Checkout form (index.blade.php) and payment instructions (payment.blade.php)
    ├── orders/              # Buyer order listing and detail views
    ├── reviews/             # Multi-item review creation form with photo/video uploaders
    ├── seller/              # Seller dashboard, catalog management, and order fulfillment
    └── filament/modals/     # Custom admin modals (order-details, payment-details, seller-details)

database/
├── factories/               # Model factories with custom roles/states
├── migrations/              # Database schema migrations (foreign keys, JSON snapshots, indexes)
└── seeders/                 # Seeders (DatabaseSeeder, AdminSeeder, CategorySeeder)

scripts/
└── compress-images.js       # Context-aware Sharp image optimization script for public/assets

tests/
├── Feature/                 # Concurrency, order lifecycle, auth, dispute, fee, follow, review tests
└── TestCase.php             # Base test configuration (whimarket_test connection, CSRF disabled)
```

---

## Development Commands

### Local Development Servers
```bash
# Start PHP development server (default port 8000)
php artisan serve

# Start Vite dev server for hot reloading (using Bun)
bun run dev

# Run background queue worker
php artisan queue:work

# Run scheduled escrow auto-completion manually
php artisan orders:auto-complete
```

### Build & Asset Compilation
```bash
# Full frontend production build (runs compress-images + vite build via Bun)
bun run build

# Standalone asset image compression using Sharp
bun run compress
```

### Testing & Code Style
```bash
# Run full PHPUnit test suite (compact mode)
php artisan test --compact

# Run full test suite via Composer (clears config cache first)
composer test

# Run a specific test file or filter by method name
php artisan test tests/Feature/OrderLifecycleTest.php
php artisan test tests/Feature/CheckoutConcurrencyTest.php
php artisan test --filter=test_zero_overselling_under_concurrent_stock_reduction

# Format modified PHP files according to project Pint rules
vendor/bin/pint --dirty --format agent

# Format all project PHP files
vendor/bin/pint --format agent
```

### Database & Seeding
```bash
# Run migrations
php artisan migrate

# Seed fresh database with demo accounts and reference data
php artisan db:seed

# Inspect routes or database configuration
php artisan route:list --path=admin
php artisan config:show database.default
```

---

## Code Conventions & Common Patterns

### Strict Typing & Method Signatures
- **Explicit Parameter & Return Types**: Declare strict return types and parameter type hints on all controller methods, service methods, and Eloquent relationships:
  ```php
  public function calculate(string|int|null $provinceNameOrCode): array
  public function followedSellers(): BelongsToMany
  public function isFollowing(Seller|int $seller): bool
  ```
- **PHP 8 Constructor Promotion**: Use `public function __construct(public ShippingRateService $shippingService) {}`. Avoid empty parameterless constructors.
- **Control Structures**: Always use curly braces for control structures, even for single-line bodies.
- **Enums**: Backed string enums with PascalCase naming and user-facing Indonesian `label()` methods:
  ```php
  enum SellerStatus: string {
      case VERIFIED = 'verified';
      case SUSPENDED = 'suspended';

      public function label(): string {
          return match ($this) {
              self::VERIFIED => 'Terverifikasi',
              self::SUSPENDED => 'Ditangguhkan',
          };
      }
  }
  ```

### Controller & Validation Conventions
- **Inline Validation**: Controllers strictly use inline `$request->validate([...], [...])` with clear Indonesian validation messages. FormRequest classes are not used in this project.
- **Dual Response Handling**: Controller actions supporting both AJAX/Alpine and traditional Blade submissions should inspect `$request->wantsJson()` or `$request->ajax()` to return JSON or redirects accordingly:
  ```php
  if ($request->wantsJson()) {
      return response()->json(['success' => true, 'message' => 'Berhasil disimpan.']);
  }
  return back()->with('success', 'Berhasil disimpan.');
  ```
- **Authorization**: Enforce authorization using Laravel policies: `Gate::authorize('view', $order)` or `$this->authorize(...)`. Administrators (`$user->isAdmin()`) bypass ownership checks.
- **Image Upload Security**: Enforce binary image verification using `@getimagesize()` on transfer proof and receipt uploads to confirm valid image headers (`IMAGETYPE_JPEG`, `IMAGETYPE_PNG`, `IMAGETYPE_WEBP`) rather than trusting client MIME extensions. Clean up obsolete files from storage upon re-upload.

### Database, Transactions & Caching
- **Transaction Safety**: All multi-table updates (checkout, order fulfillment, dispute resolution, payment approval/rejection) must be wrapped inside `DB::transaction(function () { ... })`.
- **Deadlock Prevention**: Always sort model IDs in ascending order before applying `->lockForUpdate()`.
- **Caching Strategy**:
  - Semi-static data (`ShippingZone`, `PlatformSetting`, regional lookups) must be cached with 24-hour expiration (`now()->addHours(24)`).
  - Always register cache invalidation in Eloquent model lifecycle hooks:
    ```php
    protected static function booted(): void
    {
        static::saved(function (PlatformSetting $setting) {
            Cache::forget("platform_setting_{$setting->key}");
            Cache::forget("platform_setting_active_{$setting->key}");
        });
        static::deleted(function (PlatformSetting $setting) {
            Cache::forget("platform_setting_{$setting->key}");
            Cache::forget("platform_setting_active_{$setting->key}");
        });
    }
    ```
- **Filament v5 Conventions**:
  - Tables specify `->defaultSort('created_at', 'desc')` to display newest records first.
  - Use unified action classes: `Filament\Actions\Action`, `Filament\Actions\EditAction`.
  - Use `Filament\Schemas\Schema` for form definitions.

---

## Important Files

|Path|Purpose|
|:---|:---|
|`routes/web.php`|Main web routes for Buyer storefront, Seller portal, Auth, and Regional APIs|
|`routes/console.php`|Scheduled commands (including 30-minute auto-completion of orders)|
|`app/Providers/Filament/AdminPanelProvider.php`|Filament v5 panel bootstrap, navigation groups, theme colors, and custom CSS|
|`app/Models/Order.php`|Master order model with Spatie state machine cast, address snapshots, and stock restoration logic|
|`app/States/Order/OrderStatusState.php`|Finite state machine transitions for the complete order lifecycle|
|`app/States/Dispute/DisputeStatusState.php`|Finite state machine transitions for dispute mediation lifecycle|
|`app/Http/Controllers/Buyer/CheckoutController.php`|Concurrency-locked cart checkout, shipping rate, admin fee, and order creation|
|`app/Http/Controllers/Buyer/OrderController.php`|Buyer order list, tracking, delivery confirmation, cancellation, and reordering|
|`app/Http/Controllers/Buyer/ReviewController.php`|Completed order buyer reviews with multi-photo and video unboxing uploads|
|`app/Http/Controllers/Buyer/FollowController.php`|Followed stores listing and live AJAX follow/unfollow toggle|
|`app/Http/Controllers/Seller/SellerPortalController.php`|Seller VIP onboarding, fulfillment resi input, and dispute rebuttal submission|
|`app/Services/ShippingRateService.php`|Indonesian 5-zone logistics calculation with 24-hour caching|
|`app/Services/PlatformFeeService.php`|Dynamic platform admin fee calculation service|
|`app/Models/PlatformSetting.php`|Dynamic key-value configuration model with auto-invalidating 24-hour cache|
|`app/Models/SellerAccessCode.php`|VIP onboarding access codes with quota checks, concurrency locks, and email constraints|
|`resources/css/app.css`|Tailwind CSS v4 CSS-first design tokens (`--color-primary-purple: #4F26A6`, etc.)|
|`scripts/compress-images.js`|Sharp image optimization script processing `public/assets` with dimension bounds|
|`database/seeders/DatabaseSeeder.php`|Master database seeder invoking AdminSeeder & CategorySeeder|
|`database/seeders/AdminSeeder.php`|Seeds Spatie roles and default admin account (`admin@whimarket.com`)|
|`database/seeders/CategorySeeder.php`|Seeds active storefront categories with brand colors|
|`tests/TestCase.php`|Base test configuration enforcing `whimarket_test` connection and CSRF bypass|

---

## Runtime & Tooling Preferences

- **PHP Runtime**: Requires **PHP 8.4+** (transitive dependencies `symfony/*` v8.1 and `spatie/laravel-sluggable` require `php: >=8.4.1`).
- **Node / JS Package Manager**: **Bun** is the primary package manager (`bun.lock`). Use `bun run build`, `bun run dev`, `bun run compress`, and `bun add`. Avoid committing `package-lock.json` or `pnpm-lock.yaml`.
- **Database Engine**:
  - Local development defaults to SQLite (`database/database.sqlite`) with MariaDB available.
  - Testing and production environments strictly use MariaDB (`whimarket_test` on `127.0.0.1:3306`) to support pessimistic row locking (`lockForUpdate`).
- **Git Push Policy**: **NEVER** push commits to remote (`git push origin dev` or any remote) without explicit instruction from the user. Always keep commits strictly local.
- **Local Dev Fast-Login**: Quick authentication bypass routes exist for local development (`GoogleAuthController@devLogin`):
  - Admin: `/auth/dev-login/admin`
  - Seller: `/auth/dev-login/seller`
  - Buyer: `/auth/dev-login/buyer`
  - Member (User 11): `/auth/dev-login/member`
  - Creator: `/auth/dev-login/bintang`

---

## Testing & QA

### Framework & Configuration
- **Test Runner**: **PHPUnit 12+** (`phpunit.xml`) executed via `php artisan test` or `composer test`.
- **Testing Database**: Configured to run against `whimarket_test` on MariaDB (`127.0.0.1:3306`) to accurately replicate pessimistic locking behavior.
- **Drivers**: Array drivers are used during tests for `CACHE_STORE=array`, `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync`, and `MAIL_MAILER=array`. Fast hashing via `BCRYPT_ROUNDS=4`.
- **Global CSRF Bypass**: `tests/TestCase.php` disables `PreventRequestForgery` middleware across all HTTP tests.

### Database Lifecycle Traits
1. **`DatabaseMigrations`**: Applied for multi-step transaction and concurrency tests (`OrderLifecycleTest`, `CheckoutConcurrencyTest`, `DisputeResolutionTest`, `PlatformAdminFeeTest`, `CheckoutShippingCalculationTest`).
2. **`RefreshDatabase`**: Applied for standard CRUD, auth, and boundary feature tests (`SellerFollowTest`, `SellerAccessCodeTest`, `InactiveProductPurchaseTest`, `SellerStoreStatusTest`, `PaymentRejectionAndStockRestorationTest`, `ProductReviewTest`).

### Key Test Suites
- `tests/Feature/OrderLifecycleTest.php`: Complete end-to-end flow from checkout to payment verification, shipment, buyer confirmation, and payout release.
- `tests/Feature/CheckoutConcurrencyTest.php`: Concurrency test proving zero-overselling under race conditions when variant stock is 1.
- `tests/Feature/PaymentRejectionAndStockRestorationTest.php`: Payment rejection actions (cancel vs re-upload) and stock restoration guarantees.
- `tests/Feature/PlatformAdminFeeTest.php`: Dynamic fee calculations, payment parity, and disabled fee scenarios.
- `tests/Feature/SellerAccessCodeTest.php`: VIP access code validation, quota locking, and admin resets.
- `tests/Feature/DisputeResolutionTest.php`: Dispute submission with unboxing proof, counter-evidence, and mediation outcomes.
- `tests/Feature/SellerFollowTest.php`: Store follow/unfollow toggle, guest rejection, self-follow guard, and followed stores page.
- `tests/Feature/InactiveProductPurchaseTest.php`: Security boundaries preventing purchase and display of inactive catalog items.
- `tests/Feature/ProductReviewTest.php`: Gated review submission, rating bounds, photo/video limits, and average rating aggregates.

### Testing Rules for AI Agents
1. **Always use Model Factories**: Leverage `User::factory()->buyer()`, `User::factory()->seller()`, `ProductVariant::factory()`, etc.
2. **Assign Spatie Roles in Setup**: Ensure Spatie roles are created and assigned idempotently:
   ```php
   Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
   $user->assignRole('buyer');
   ```
3. **State Machine Assertions**: Assert Spatie Model States via the state object contract:
   ```php
   $this->assertTrue($order->status->equals(Paid::class));
   ```
4. **Mock File Uploads**: Use `Storage::fake('public')` and `UploadedFile::fake()->create(...)`.
5. **No Padding Tests**: Write tests defending observable contracts, boundaries, and invariants. Do not write tests for trivial framework wiring.
6. **Always Verify Pint and Tests**: Run `vendor/bin/pint --dirty --format agent` and `php artisan test --compact` before completing any changes.
