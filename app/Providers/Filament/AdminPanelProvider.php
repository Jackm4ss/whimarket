<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\AdminHeroWidget;
use App\Filament\Widgets\EscrowMetricWidget;
use App\Filament\Widgets\OrderStatusBreakdownWidget;
use App\Filament\Widgets\RecentOrdersWidget;
use App\Filament\Widgets\RevenueMetricWidget;
use App\Filament\Widgets\SalesGoalWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsIconAlias;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaBoiteACode\FilamentDashboardWidgets\FilamentDashboardWidgetsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName('WhiMarket Admin')
            ->brandLogo(asset('assets/logo-whimarket.png'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('assets/logo-whimarket.png'))
            ->colors([
                'primary' => Color::hex('#4F26A6'),
                'amber' => Color::Amber,
            ])
            ->darkMode(false)
            ->icons([
                PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => Heroicon::OutlinedBars3,
                PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => Heroicon::OutlinedBars3,
                PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON_RTL => Heroicon::OutlinedBars3,
                PanelsIconAlias::SIDEBAR_EXPAND_BUTTON_RTL => Heroicon::OutlinedBars3,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(true)
            ->navigationGroups([
                NavigationGroup::make('Pesanan & Transaksi')
                    ->collapsible(true),
                NavigationGroup::make('Katalog & Produk')
                    ->collapsible(true),
                NavigationGroup::make('Mitra Toko (Seller)')
                    ->collapsible(true),
                NavigationGroup::make('Keuangan & Sengketa')
                    ->collapsible(true),
                NavigationGroup::make('Pengaturan & Logistik')
                    ->collapsible(true),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('
                    <style>
                        #fi-main-sidebar,
                        .fi-sidebar {
                            background-color: #FFFFFF !important;
                            border-right: 1px solid #E2E8F0 !important;
                            box-shadow: 1px 0 3px 0 rgba(0, 0, 0, 0.02) !important;
                        }
                        .fi-sidebar-header {
                            background-color: #FFFFFF !important;
                            border-bottom: 1px solid #F1F5F9 !important;
                        }
                        .fi-topbar {
                            border-bottom: 1px solid #E2E8F0 !important;
                        }
                        .fi-sidebar-nav {
                            padding: 1rem 0.75rem !important;
                        }
                        .fi-sidebar-nav-groups {
                            gap: 0.375rem !important;
                        }
                        .fi-sidebar-nav-groups > .fi-sidebar-group:not(:first-child) {
                            border-top: 1px solid #F1F5F9 !important;
                            margin-top: 0.375rem !important;
                            padding-top: 0.375rem !important;
                        }
                        .fi-sidebar-group-btn {
                            min-height: 1.625rem !important;
                            height: 1.625rem !important;
                            padding: 0.125rem 0.5rem !important;
                            margin-bottom: 0.125rem !important;
                        }
                        .fi-sidebar-group-label {
                            font-size: 0.6875rem !important;
                            font-weight: 700 !important;
                            letter-spacing: 0.05em !important;
                            text-transform: uppercase !important;
                            color: #94A3B8 !important;
                        }
                        .fi-sidebar-group-items {
                            gap: 0.125rem !important;
                            margin-top: 0.125rem !important;
                        }
                        .fi-sidebar-item-btn {
                            min-height: 2.125rem !important;
                            height: 2.125rem !important;
                            padding: 0.25rem 0.625rem !important;
                            border-radius: 0.5rem !important;
                            font-size: 0.8125rem !important;
                            gap: 0.5rem !important;
                        }
                        .fi-sidebar-item-icon {
                            width: 1.125rem !important;
                            height: 1.125rem !important;
                        }
                        /* Clickable Seller Store Link in Table */
                        .fi-ta-col-has-column-url {
                            color: #4F26A6 !important;
                            font-weight: 600 !important;
                            transition: opacity 0.15s ease-in-out !important;
                        }
                        .fi-ta-col-has-column-url:hover {
                            opacity: 0.8 !important;
                            text-decoration: underline !important;
                        }
                        /* Order Status Filter Tabs Button Affordance & Mobile Swipe Styles */
                        .fi-sc-tabs {
                            position: relative !important;
                        }
                        @media (max-width: 768px) {
                            .fi-sc-tabs::before {
                                content: "Filter Status (Geser ke samping untuk kategori lain →)";
                                display: block;
                                font-size: 0.75rem;
                                font-weight: 600;
                                color: #64748B;
                                margin-bottom: 0.5rem;
                                padding-left: 0.25rem;
                                letter-spacing: 0.01em;
                            }
                        }
                        @media (min-width: 769px) {
                            .fi-sc-tabs::before {
                                display: none !important;
                            }
                        }
                        nav.fi-tabs {
                            background-color: #F8FAFC !important;
                            border: 1px solid #E2E8F0 !important;
                            border-radius: 0.75rem !important;
                            padding: 0.375rem !important;
                            gap: 0.375rem !important;
                            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03) !important;
                            overflow-x: auto !important;
                            overflow-y: hidden !important;
                            flex-wrap: nowrap !important;
                            -webkit-overflow-scrolling: touch !important;
                            scroll-behavior: smooth !important;
                            scrollbar-width: thin !important;
                            scrollbar-color: #CBD5E1 #F1F5F9 !important;
                        }
                        @media (max-width: 768px) {
                            nav.fi-tabs {
                                padding-right: 2.5rem !important;
                            }
                        }
                        nav.fi-tabs::-webkit-scrollbar {
                            height: 4px !important;
                        }
                        nav.fi-tabs::-webkit-scrollbar-track {
                            background: #F1F5F9 !important;
                            border-radius: 9999px !important;
                        }
                        nav.fi-tabs::-webkit-scrollbar-thumb {
                            background: #CBD5E1 !important;
                            border-radius: 9999px !important;
                        }
                        nav.fi-tabs::-webkit-scrollbar-thumb:hover {
                            background: #94A3B8 !important;
                        }
                        .fi-tabs-item {
                            border-radius: 0.5rem !important;
                            padding: 0.4375rem 0.875rem !important;
                            font-size: 0.8125rem !important;
                            font-weight: 600 !important;
                            cursor: pointer !important;
                            transition: all 0.15s ease-in-out !important;
                            border: 1px solid #E2E8F0 !important;
                            background-color: #FFFFFF !important;
                            color: #334155 !important;
                            display: inline-flex !important;
                            align-items: center !important;
                            gap: 0.5rem !important;
                            flex-shrink: 0 !important;
                            white-space: nowrap !important;
                            user-select: none !important;
                            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04) !important;
                        }
                        .fi-tabs-item:hover {
                            background-color: #F1F5F9 !important;
                            border-color: #CBD5E1 !important;
                            color: #0F172A !important;
                            transform: translateY(-1px);
                            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.06) !important;
                        }
                        .fi-tabs-item:active {
                            transform: translateY(0) !important;
                        }
                        .fi-tabs-item:not(.fi-active) .fi-tabs-item-label {
                            color: #334155 !important;
                            font-weight: 600 !important;
                        }
                        .fi-tabs-item:hover .fi-tabs-item-label {
                            color: #0F172A !important;
                        }
                        .fi-tabs-item.fi-active {
                            background-color: #4F26A6 !important;
                            border-color: #4F26A6 !important;
                            color: #FFFFFF !important;
                            box-shadow: 0 2px 6px 0 rgba(79, 38, 166, 0.3) !important;
                            transform: none !important;
                        }
                        .fi-tabs-item.fi-active .fi-tabs-item-label {
                            color: #FFFFFF !important;
                            font-weight: 600 !important;
                        }
                        .fi-tabs-item.fi-active svg,
                        .fi-tabs-item.fi-active .fi-icon {
                            color: #FFFFFF !important;
                        }
                        .fi-tabs-item.fi-active .fi-badge {
                            background-color: rgba(255, 255, 255, 0.25) !important;
                            color: #FFFFFF !important;
                            border-color: transparent !important;
                            font-weight: 700 !important;
                        }
                        .fi-tabs-item.fi-active .fi-badge .fi-badge-label {
                            color: #FFFFFF !important;
                        }
                        .fi-tabs-item:not(.fi-active) svg,
                        .fi-tabs-item:not(.fi-active) .fi-icon {
                            color: #64748B !important;
                            width: 1rem !important;
                            height: 1rem !important;
                        }
                        .fi-tabs-item:hover svg,
                        .fi-tabs-item:hover .fi-icon {
                            color: #4F26A6 !important;
                        }
                        .fi-tabs-item:not(.fi-active) .fi-badge {
                            border: 1px solid rgba(0, 0, 0, 0.06) !important;
                            font-weight: 600 !important;
                        }
                        /* Modal Solid Background, Backdrop Dimming & Elevation */
                        .fi-modal-window,
                        .fi-modal-content,
                        [role="dialog"] .fi-modal-window {
                            background-color: #FFFFFF !important;
                            opacity: 1 !important;
                            transform: none !important;
                            border: 1px solid #E2E8F0 !important;
                            border-radius: 1rem !important;
                            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
                        }
                        .fi-modal-close-overlay {
                            background-color: rgba(15, 23, 42, 0.65) !important;
                            backdrop-filter: blur(4px) !important;
                            opacity: 1 !important;
                        }
                        .fi-modal-header {
                            background-color: #FFFFFF !important;
                            border-bottom: 1px solid #F1F5F9 !important;
                            padding: 1rem 1.5rem !important;
                        }
                        .fi-modal-footer {
                            background-color: #F8FAFC !important;
                            border-top: 1px solid #F1F5F9 !important;
                            padding: 0.875rem 1.5rem !important;
                        }
                    </style>
                ')
            )
            ->plugins([
                FilamentDashboardWidgetsPlugin::make(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AdminHeroWidget::class,
                RevenueMetricWidget::class,
                EscrowMetricWidget::class,
                SalesGoalWidget::class,
                OrderStatusBreakdownWidget::class,
                RecentOrdersWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
