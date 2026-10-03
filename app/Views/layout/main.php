<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Toko Ritel Makmur - Inventory System' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex min-h-screen">

    <?php 
        $role = session()->get('role') ?? 'admin'; 
        $fullname = session()->get('fullname') ?? 'User';
    ?>

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between p-5 hidden md:flex">
        <div>
            <!-- Brand Logo -->
            <div class="flex items-center gap-3 px-2 py-3 mb-6">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white shadow-sm">
                    RM
                </div>
                <div>
                    <h1 class="font-bold text-white text-base leading-tight">Ritel Makmur</h1>
                    <span class="text-xs text-slate-400">Inventory & POS System</span>
                </div>
            </div>

            <!-- Navigation Links Berdasarkan Role -->
            <nav class="space-y-1">
                <?php if (in_array($role, ['admin'])): ?>
                    <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= url_is('dashboard*') || url_is('/') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                <?php endif; ?>

                <?php if (in_array($role, ['admin', 'pembelian'])): ?>
                    <a href="<?= base_url('products') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= url_is('products*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Master Produk
                    </a>
                <?php endif; ?>

                <?php if (in_array($role, ['admin', 'gudang'])): ?>
                    <a href="<?= base_url('inventory') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= url_is('inventory') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Stok Multi-Gudang
                    </a>

                    <a href="<?= base_url('inventory/transfer') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= url_is('inventory/transfer*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        Mutasi Stok
                    </a>
                <?php endif; ?>

                <?php if (in_array($role, ['admin', 'kasir'])): ?>
                    <a href="<?= base_url('sales/create') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= url_is('sales/create*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        POS (Penjualan Gerai)
                    </a>

                    <a href="<?= base_url('sales/history') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= url_is('sales/history*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Transaksi
                    </a>
                <?php endif; ?>

                <?php if (in_array($role, ['admin', 'pembelian'])): ?>
                    <a href="<?= base_url('purchase') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= url_is('purchase*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Pembelian (Restock)
                    </a>
                <?php endif; ?>

                <?php if (in_array($role, ['admin'])): ?>
                    <a href="<?= base_url('reports') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= url_is('reports*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Laporan Rekapitulasi
                    </a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="pt-4 border-t border-slate-800 text-xs text-slate-400 flex items-center justify-between">
            <div>
                <span class="block text-[10px] uppercase tracking-wider text-slate-500">Aktor Saat Ini:</span>
                <span class="font-bold text-white uppercase text-[11px]"><?= $role ?></span>
            </div>
            <a href="<?= base_url('logout') ?>" onclick="return confirm('Keluar dari aplikasi?')" class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white font-semibold transition">
                Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Topbar Header -->
        <header class="h-16 bg-white border-b border-slate-200/80 px-8 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800"><?= $page_heading ?? 'Overview' ?></h2>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-500">Halo, <strong class="text-slate-800"><?= $fullname ?></strong></span>
                <a href="<?= base_url('logout') ?>" class="text-xs text-rose-600 hover:underline font-semibold ml-2">Keluar</a>
            </div>
        </header>

        <!-- Dynamic Body Content -->
        <main class="flex-1 overflow-y-auto p-8">
            <!-- Flash Alert -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>

</body>
</html>