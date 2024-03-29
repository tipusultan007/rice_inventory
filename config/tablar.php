<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    | Here you can change the default title of your admin panel.
    |
    */

    'title' => 'M/S S.A Rice Agency',
    'title_prefix' => '',
    'title_postfix' => '',
    'bottom_title' => 'M/S S.A Rice Agency',
    'current_version' => 'v1.0',


    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    */

    'logo' => '<b>Tab</b>LAR',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can set up an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'assets/tablar-logo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
     *
     * Default path is 'resources/views/vendor/tablar' as null. Set your custom path here If you need.
     */

    'views_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look at the layout section here:
    |
    */

    'layout' => 'combo',
    //boxed, combo, condensed, fluid, fluid-vertical, horizontal, navbar-overlap, navbar-sticky, rtl, vertical, vertical-right, vertical-transparent

    'layout_light_sidebar' => false,
    'layout_light_topbar' => false,
    'layout_enable_top_header' => false,

    /*
    |--------------------------------------------------------------------------
    | Sticky Navbar for Top Nav
    |--------------------------------------------------------------------------
    |
    | Here you can enable/disable the sticky functionality of Top Navigation Bar.
    |
    | For detailed instructions, you can look at the Top Navigation Bar classes here:
    |
    */

    'sticky_top_nav_bar' => false,

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions, you can look at the admin panel classes here:
    |
    */

    'classes_body' => '',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions, you can look at the urls section here:
    |
    */

    'use_route_url' => true,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password.request',
    'password_email_url' => 'password.email',
    'profile_url' => false,
    'setting_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Display Alert
    |--------------------------------------------------------------------------
    |
    | Display Alert Visibility.
    |
    */
    'display_alert' => false,

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    |
    */

    'menu' => [
        // Navbar items:
        [
            'text' => 'Home',
            'icon' => 'ti ti-home',
            'url' => 'home'
        ],
        [
            'text' => 'পণ্য',
            'route' => 'products.index',
            'icon' => 'ti ti-building-store',
        ],
        [
            'text' => 'ক্রয়',
            'route' => 'purchases.index',
            'icon' => 'ti ti-building-store',
        ],
        [
            'text' => 'বিক্রয়',
            'route' => 'sales.index',
            'icon' => 'ti ti-shopping-cart',
        ],
        [
            'text' => 'ক্রেতা',
            'route' => 'customers.index',
            'icon' => 'ti ti-users',
        ],
        [
            'text' => 'সরবরাহকারী',
            'route' => 'suppliers.index',
            'icon' => 'ti ti-users',
        ],
        [
            'text' => 'ব্যবহারকারীসমূহ',
            'route' => 'users.index',
            'icon' => 'ti ti-users',
        ],
        [
            'text' => 'অ্যাকাউন্টসমূহ',
            'route' => 'accounts.index',
            'icon' => 'ti ti-currency-taka',
        ],
        [
            'text' => 'লোন ম্যানেজমেন্ট',
            'url' => '#',
            'icon' => 'ti ti-coin-taka',
            'submenu' => [
                [
                    'text' => 'সকল লোন',
                    'route' => 'loans.index',
                    'icon' => 'ti ti-coin-taka',
                ],
                [
                    'text' => 'লোন পেমেন্ট',
                    'route' => 'loans.transactions',
                    'icon' => 'ti ti-category',
                ],
            ]
        ],
        [
            'text' => 'ব্যালেন্স ট্রান্সফার',
            'route' => 'balance_transfers.index',
            'icon' => 'ti ti-currency-taka',
        ],
        [
            'text' => 'লেনদেন',
            'url' => '#',
            'icon' => 'ti ti-currency-taka',
            'submenu' => [
                [
                    'text' => 'সকল লেনদেন',
                    'route' => 'transactions.index',
                    'icon' => 'ti ti-cash-banknote',
                ],
                [
                    'text' => 'ক্রেতা\'র লেনদেন',
                    'route' => 'transactions.customer',
                    'icon' => 'ti ti-cash-banknote',
                ],
                [
                    'text' => 'সরবরাহকারী\'র লেনদেন',
                    'route' => 'transactions.supplier',
                    'icon' => 'ti ti-cash-banknote',
                ],
            ]
        ],
        [
            'text' => 'লোন ম্যানেজমেন্ট',
            'url' => '#',
            'icon' => 'ti ti-coin-taka',
            'submenu' => [
                [
                    'text' => 'সকল লোন',
                    'route' => 'loans.index',
                    'icon' => 'ti ti-coin-taka',
                ],
                [
                    'text' => 'লোন পেমেন্ট',
                    'route' => 'loans.transactions',
                    'icon' => 'ti ti-category',
                ],
            ]
        ],
        [
            'text' => 'ব্যয় সমুহ',
            'url' => '#',
            'icon' => 'ti ti-coin-taka',
            'submenu' => [
                [
                    'text' => 'ব্যয়',
                    'route' => 'expenses.index',
                    'icon' => 'ti ti-coin-taka',
                ],
                [
                    'text' => 'ব্যয় ক্যাটেগরি',
                    'route' => 'expense_categories.index',
                    'icon' => 'ti ti-category',
                ],
            ]
        ],
        [
            'text' => 'সম্পদ',
            'route' => 'asset.index',
            'icon' => 'ti ti-box',
        ],
        [
            'text' => 'রিপোর্ট',
            'url' => '#',
            'icon' => 'ti ti-report-analytics',
            'submenu' => [
                [
                    'text' => 'দৈনিক রিপোর্ট',
                    'route' => 'report.daily',
                    'icon' => 'ti ti-report',
                ],
                [
                    'text' => 'পেমেন্ট রিপোর্ট',
                    'route' => 'report.payment',
                    'icon' => 'ti ti-report',
                ]
            ]
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    |
    */

    'filters' => [
        TakiElias\Tablar\Menu\Filters\GateFilter::class,
        TakiElias\Tablar\Menu\Filters\HrefFilter::class,
        TakiElias\Tablar\Menu\Filters\SearchFilter::class,
        TakiElias\Tablar\Menu\Filters\ActiveFilter::class,
        TakiElias\Tablar\Menu\Filters\ClassesFilter::class,
        TakiElias\Tablar\Menu\Filters\LangFilter::class,
        TakiElias\Tablar\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Vite
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Vite support.
    |
    | For detailed instructions you can look the Vite here:
    | https://laravel-vite.dev
    |
    */

    'vite' => true,
];
