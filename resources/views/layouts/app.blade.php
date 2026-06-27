<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPPG Sukasejati 2') }} - @yield('title', 'Dashboard')</title>
        <meta name="description" content="Sistem Informasi Manajemen SPPG Sukasejati 2 - Badan Gizi Nasional">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --sidebar-width: 260px;
                --primary: #6366f1;
                --primary-dark: #4f46e5;
                --primary-light: #818cf8;
                --accent: #06b6d4;
                --success: #10b981;
                --warning: #f59e0b;
                --danger: #ef4444;
                --surface: #0f172a;
                --surface-light: #1e293b;
                --surface-card: #ffffff;
                --text-primary: #0f172a;
                --text-secondary: #64748b;
                --text-light: #94a3b8;
                --border: #e2e8f0;
            }

            * { font-family: 'Inter', sans-serif; }

            /* Sidebar */
            .sidebar {
                width: var(--sidebar-width);
                background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
                min-height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                z-index: 40;
                transition: transform 0.3s ease;
                overflow-y: auto;
            }

            .sidebar-brand {
                padding: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                border-bottom: 1px solid rgba(255,255,255,0.08);
            }

            .sidebar-brand-icon {
                width: 42px;
                height: 42px;
                background: linear-gradient(135deg, var(--primary), var(--accent));
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                font-weight: 800;
                color: white;
                box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
            }

            .sidebar-brand-text h1 {
                color: white;
                font-size: 1.1rem;
                font-weight: 700;
                letter-spacing: -0.025em;
            }
            .sidebar-brand-text p {
                color: var(--text-light);
                font-size: 0.7rem;
                font-weight: 400;
                margin-top: 2px;
            }

            .sidebar-nav {
                padding: 1rem 0.75rem;
            }

            .sidebar-section-title {
                color: var(--text-light);
                font-size: 0.65rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                padding: 0.75rem 0.75rem 0.5rem;
            }

            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.65rem 0.75rem;
                border-radius: 10px;
                color: #cbd5e1;
                font-size: 0.85rem;
                font-weight: 500;
                transition: all 0.2s ease;
                margin-bottom: 2px;
                text-decoration: none;
            }

            .sidebar-link:hover {
                background: rgba(255,255,255,0.08);
                color: white;
                transform: translateX(2px);
            }

            .sidebar-link.active {
                background: linear-gradient(135deg, rgba(99,102,241,0.3), rgba(6,182,212,0.15));
                color: white;
                box-shadow: 0 0 20px rgba(99,102,241,0.15);
                border: 1px solid rgba(99,102,241,0.2);
            }

            .sidebar-link svg {
                width: 20px;
                height: 20px;
                flex-shrink: 0;
                opacity: 0.7;
            }

            .sidebar-link.active svg,
            .sidebar-link:hover svg {
                opacity: 1;
            }

            /* Main Content */
            .main-content {
                margin-left: var(--sidebar-width);
                min-height: 100vh;
                background: #f1f5f9;
            }

            .topbar {
                background: white;
                border-bottom: 1px solid var(--border);
                padding: 0.75rem 2rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 30;
                backdrop-filter: blur(10px);
                background: rgba(255,255,255,0.9);
            }

            .topbar-left h2 {
                font-size: 1.15rem;
                font-weight: 700;
                color: var(--text-primary);
            }
            .topbar-left p {
                font-size: 0.8rem;
                color: var(--text-secondary);
            }

            .topbar-right {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .user-menu {
                position: relative;
            }

            .user-btn {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.4rem 0.75rem;
                border-radius: 10px;
                border: 1px solid var(--border);
                background: white;
                cursor: pointer;
                transition: all 0.2s;
                font-size: 0.85rem;
                color: var(--text-primary);
            }

            .user-btn:hover {
                border-color: var(--primary);
                box-shadow: 0 2px 8px rgba(99,102,241,0.1);
            }

            .user-avatar {
                width: 30px;
                height: 30px;
                background: linear-gradient(135deg, var(--primary), var(--accent));
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
                font-size: 0.75rem;
            }

            .user-dropdown {
                position: absolute;
                right: 0;
                top: calc(100% + 8px);
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.12);
                border: 1px solid var(--border);
                min-width: 200px;
                padding: 0.5rem;
                display: none;
                z-index: 50;
            }

            .user-dropdown.show { display: block; }

            .user-dropdown a,
            .user-dropdown button {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 0.75rem;
                border-radius: 8px;
                font-size: 0.85rem;
                color: var(--text-secondary);
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.15s;
            }

            .user-dropdown a:hover,
            .user-dropdown button:hover {
                background: #f8fafc;
                color: var(--text-primary);
            }

            .page-content {
                padding: 1.5rem 2rem;
            }

            /* Cards */
            .stat-card {
                background: white;
                border-radius: 16px;
                padding: 1.5rem;
                border: 1px solid var(--border);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                border-radius: 16px 16px 0 0;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            }

            .stat-card.primary::before { background: linear-gradient(90deg, var(--primary), var(--primary-light)); }
            .stat-card.success::before { background: linear-gradient(90deg, var(--success), #34d399); }
            .stat-card.warning::before { background: linear-gradient(90deg, var(--warning), #fbbf24); }
            .stat-card.danger::before { background: linear-gradient(90deg, var(--danger), #f87171); }
            .stat-card.accent::before { background: linear-gradient(90deg, var(--accent), #22d3ee); }

            .stat-card-icon {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
            }

            .stat-card.primary .stat-card-icon { background: rgba(99,102,241,0.1); color: var(--primary); }
            .stat-card.success .stat-card-icon { background: rgba(16,185,129,0.1); color: var(--success); }
            .stat-card.warning .stat-card-icon { background: rgba(245,158,11,0.1); color: var(--warning); }
            .stat-card.danger .stat-card-icon { background: rgba(239,68,68,0.1); color: var(--danger); }
            .stat-card.accent .stat-card-icon { background: rgba(6,182,212,0.1); color: var(--accent); }

            .stat-card-value {
                font-size: 1.75rem;
                font-weight: 800;
                color: var(--text-primary);
                letter-spacing: -0.025em;
            }

            .stat-card-label {
                font-size: 0.8rem;
                color: var(--text-secondary);
                margin-top: 0.25rem;
                font-weight: 500;
            }

            /* Data Table */
            .data-table-wrapper {
                background: white;
                border-radius: 16px;
                border: 1px solid var(--border);
                overflow: hidden;
            }

            .data-table-header {
                padding: 1.25rem 1.5rem;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .data-table-header h3 {
                font-size: 1rem;
                font-weight: 700;
                color: var(--text-primary);
            }

            .data-table {
                width: 100%;
                border-collapse: collapse;
            }

            .data-table th {
                padding: 0.75rem 1.5rem;
                text-align: left;
                font-size: 0.75rem;
                font-weight: 600;
                color: var(--text-secondary);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                background: #f8fafc;
                border-bottom: 1px solid var(--border);
            }

            .data-table td {
                padding: 0.85rem 1.5rem;
                font-size: 0.85rem;
                color: var(--text-primary);
                border-bottom: 1px solid #f1f5f9;
            }

            .data-table tbody tr {
                transition: background 0.15s;
            }

            .data-table tbody tr:hover {
                background: #f8fafc;
            }

            .data-table tbody tr:last-child td {
                border-bottom: none;
            }

            /* Buttons */
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.55rem 1.25rem;
                border-radius: 10px;
                font-size: 0.85rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                border: none;
                text-decoration: none;
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: white;
                box-shadow: 0 2px 8px rgba(99,102,241,0.3);
            }
            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 16px rgba(99,102,241,0.4);
            }

            .btn-success {
                background: linear-gradient(135deg, var(--success), #059669);
                color: white;
            }

            .btn-warning {
                background: linear-gradient(135deg, var(--warning), #d97706);
                color: white;
            }

            .btn-danger {
                background: linear-gradient(135deg, var(--danger), #dc2626);
                color: white;
            }

            .btn-outline {
                background: white;
                color: var(--text-primary);
                border: 1px solid var(--border);
            }
            .btn-outline:hover {
                border-color: var(--primary);
                color: var(--primary);
            }

            .btn-sm {
                padding: 0.35rem 0.75rem;
                font-size: 0.78rem;
            }

            /* Badge */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: 0.2rem 0.65rem;
                border-radius: 100px;
                font-size: 0.72rem;
                font-weight: 600;
                letter-spacing: 0.02em;
            }

            .badge-draft { background: #f1f5f9; color: #475569; }
            .badge-dikirim { background: #dbeafe; color: #1d4ed8; }
            .badge-disetujui { background: #fef3c7; color: #b45309; }
            .badge-selesai { background: #d1fae5; color: #065f46; }
            .badge-belum { background: #fee2e2; color: #991b1b; }
            .badge-sebagian { background: #fef3c7; color: #92400e; }
            .badge-lunas { background: #d1fae5; color: #065f46; }

            /* Form */
            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                display: block;
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: 0.4rem;
            }

            .form-input {
                width: 100%;
                padding: 0.6rem 0.85rem;
                border: 1px solid var(--border);
                border-radius: 10px;
                font-size: 0.85rem;
                color: var(--text-primary);
                transition: all 0.2s;
                background: white;
            }

            .form-input:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            }

            .form-select {
                width: 100%;
                padding: 0.6rem 0.85rem;
                border: 1px solid var(--border);
                border-radius: 10px;
                font-size: 0.85rem;
                color: var(--text-primary);
                background: white;
                cursor: pointer;
            }

            .form-select:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            }

            .form-error {
                color: var(--danger);
                font-size: 0.78rem;
                margin-top: 0.25rem;
            }

            /* Search bar */
            .search-bar {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.45rem 0.85rem;
                border: 1px solid var(--border);
                border-radius: 10px;
                background: white;
                transition: all 0.2s;
                min-width: 250px;
            }

            .search-bar:focus-within {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            }

            .search-bar input {
                border: none;
                outline: none;
                font-size: 0.85rem;
                width: 100%;
                background: transparent;
            }

            .search-bar svg {
                width: 18px;
                height: 18px;
                color: var(--text-light);
                flex-shrink: 0;
            }

            /* Toast */
            .toast-container {
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 100;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .toast {
                padding: 0.75rem 1.25rem;
                border-radius: 12px;
                font-size: 0.85rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                animation: slideIn 0.3s ease, fadeOut 0.3s ease 4.7s;
                box-shadow: 0 4px 20px rgba(0,0,0,0.12);
                max-width: 400px;
            }

            .toast-success {
                background: var(--success);
                color: white;
            }

            .toast-error {
                background: var(--danger);
                color: white;
            }

            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }

            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }

            /* Grid */
            .grid-cols-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
            .grid-cols-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
            .grid-cols-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }

            /* Responsive */
            .mobile-toggle {
                display: none;
                background: none;
                border: none;
                padding: 0.5rem;
                cursor: pointer;
                color: var(--text-primary);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 35;
            }

            @media (max-width: 1024px) {
                .sidebar {
                    transform: translateX(-100%);
                }
                .sidebar.open {
                    transform: translateX(0);
                }
                .sidebar-overlay.show {
                    display: block;
                }
                .main-content {
                    margin-left: 0;
                }
                .mobile-toggle {
                    display: block;
                }
                .grid-cols-4 { grid-template-columns: repeat(2, 1fr); }
                .grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
            }

            @media (max-width: 640px) {
                .grid-cols-4,
                .grid-cols-3,
                .grid-cols-2 { grid-template-columns: 1fr; }
                .page-content { padding: 1rem; }
                .topbar { padding: 0.75rem 1rem; }
                .data-table-header { flex-direction: column; align-items: stretch; }
            }

            /* Pagination */
            .pagination-wrapper {
                padding: 1rem 1.5rem;
                border-top: 1px solid var(--border);
            }

            .pagination-wrapper nav {
                display: flex;
                justify-content: center;
            }

            .pagination-wrapper svg {
                width: 20px;
                height: 20px;
            }

            /* Modal */
            .modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 60;
                display: none;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(4px);
            }

            .modal-overlay.show { display: flex; }

            .modal {
                background: white;
                border-radius: 16px;
                padding: 2rem;
                max-width: 420px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            }

            .modal h3 {
                font-size: 1.1rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .modal p {
                color: var(--text-secondary);
                font-size: 0.85rem;
                margin-bottom: 1.5rem;
            }

            .modal-actions {
                display: flex;
                gap: 0.75rem;
                justify-content: flex-end;
            }
        </style>
    </head>
    <body>
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">M</div>
                <div class="sidebar-brand-text">
                    <h1>SPPG Sukasejati 2</h1>
                    <p>Badan Gizi Nasional</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-section-title">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->hasAnyRole(['bendahara', 'admin_gudang', 'pengawas']))
                <div class="sidebar-section-title">Master Data</div>

                @if(auth()->user()->hasRole('bendahara'))
                <a href="{{ route('suppliers.index') }}" class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016A3.001 3.001 0 0021 9.349m-18 0a2.99 2.99 0 00.621-1.82L4.25 4.5h15.5l.63 3.03a2.99 2.99 0 00.62 1.82" />
                    </svg>
                    Supplier
                </a>
                @endif

                @if(auth()->user()->hasAnyRole(['admin_gudang', 'bendahara']))
                <a href="{{ route('bahan-baku.index') }}" class="sidebar-link {{ request()->routeIs('bahan-baku.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    Bahan Baku
                </a>
                @endif
                @endif

                @if(auth()->user()->hasAnyRole(['bendahara', 'admin_gudang', 'pengawas']))
                <div class="sidebar-section-title">Transaksi</div>
                <a href="{{ route('purchase-orders.index') }}" class="sidebar-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Purchase Order
                </a>
                @endif

                @if(auth()->user()->hasAnyRole(['kepala_dapur', 'bendahara', 'pengawas']))
                <div class="sidebar-section-title">Operasional Dapur</div>
                <a href="{{ route('produksi.index') }}" class="sidebar-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    Produksi MBG
                </a>
                <a href="{{ route('distribusi.index') }}" class="sidebar-link {{ request()->routeIs('distribusi.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                    Distribusi
                </a>
                @endif

                @if(auth()->user()->hasAnyRole(['admin_gudang', 'bendahara', 'pengawas', 'kepala_dapur']))
                <div class="sidebar-section-title">Gudang</div>
                <a href="{{ route('stok-gudang.index') }}" class="sidebar-link {{ request()->routeIs('stok-gudang.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    Stok Gudang
                </a>
                @if(auth()->user()->hasAnyRole(['admin_gudang', 'bendahara', 'pengawas']))
                <a href="{{ route('kartu-stok.index') }}" class="sidebar-link {{ request()->routeIs('kartu-stok.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Kartu Stok
                </a>
                @endif
                @endif

                @if(auth()->user()->hasAnyRole(['bendahara', 'pengawas']))
                <div class="sidebar-section-title">Keuangan</div>
                <a href="{{ route('invoices.index') }}" class="sidebar-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Invoice Tagihan
                </a>
                
                @if(auth()->user()->hasRole('bendahara'))
                <a href="{{ route('pengeluaran.index') }}" class="sidebar-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                    Pengeluaran Operasional
                </a>
                @endif
                
                <a href="{{ route('laporan-keuangan.index') }}" class="sidebar-link {{ request()->routeIs('laporan-keuangan.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v2.25A2.25 2.25 0 0011.25 21h1.5A2.25 2.25 0 0015 18.75V16.5m-13.5-9L12 3l10.5 4.5v9a10.5 10.5 0 01-21 0v-9z" />
                    </svg>
                    Laporan Keuangan
                </a>
                @endif

                @if(auth()->user()->hasRole('bendahara'))
                <div class="sidebar-section-title">Sistem</div>
                <a href="{{ route('settings.edit') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan
                </a>
                @endif
                
                <div class="sidebar-section-title">Akun</div>
                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Profil
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <div class="topbar">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <button class="mobile-toggle" onclick="toggleSidebar()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div class="topbar-left">
                        <h2>@yield('title', 'Dashboard')</h2>
                        <p>@yield('subtitle', 'Selamat datang di SPPG Sukasejati 2')</p>
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="user-menu">
                        <button class="user-btn" onclick="toggleDropdown()">
                            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <span>{{ auth()->user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <div style="padding:0.5rem 0.75rem; border-bottom:1px solid var(--border); margin-bottom:0.25rem;">
                                <div style="font-size:0.75rem; color:var(--text-light);">Login sebagai</div>
                                <div style="font-size:0.82rem; font-weight:600; color:var(--primary); text-transform:capitalize;">{{ auth()->user()->roles->first()?->name ?? 'User' }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast Notifications -->
            <div class="toast-container" id="toastContainer">
                @if(session('success'))
                    <div class="toast toast-success" id="toast">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="toast toast-error" id="toast">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>
        </div>

        <script>
            function toggleSidebar() {
                document.getElementById('sidebar').classList.toggle('open');
                document.getElementById('sidebarOverlay').classList.toggle('show');
            }

            function toggleDropdown() {
                document.getElementById('userDropdown').classList.toggle('show');
            }

            // Close dropdown on outside click
            document.addEventListener('click', function(e) {
                const menu = document.querySelector('.user-menu');
                if (menu && !menu.contains(e.target)) {
                    document.getElementById('userDropdown').classList.remove('show');
                }
            });

            // Auto-hide toast after 5s
            setTimeout(() => {
                const toast = document.getElementById('toast');
                if (toast) toast.remove();
            }, 5000);
        </script>

        @stack('scripts')
    </body>
</html>
