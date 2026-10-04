<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Include Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Quick Action Shortcuts Bar -->
<div class="mb-8 bg-gradient-to-r from-slate-900 to-indigo-950 p-6 rounded-2xl text-white flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
    <div>
        <h3 class="font-bold text-base">Akses Cepat Operasional</h3>
        <p class="text-xs text-slate-300 mt-0.5">Pilih aksi langsung untuk transaksi gerai, transfer barang, atau pemesanan ke supplier.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="<?= base_url('sales/create') ?>" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition flex items-center gap-1.5">
            <span>+</span> Input Penjualan Kasir
        </a>
        <a href="<?= base_url('inventory/transfer') ?>" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition flex items-center gap-1.5">
            <span>⇄</span> Mutasi Stok Gudang
        </a>
        <a href="<?= base_url('purchase/create') ?>" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition flex items-center gap-1.5">
            <span>📋</span> Terbitkan PO Pembelian
        </a>
    </div>
</div>

<!-- Stat Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <?php foreach ($stocksByLocation as $loc): ?>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-[10px] tracking-wider uppercase font-extrabold text-slate-400">
                    <?= strtoupper($loc['type']) ?>
                </span>
                <h3 class="font-semibold text-slate-700 text-sm mt-0.5"><?= $loc['location_name'] ?></h3>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900"><?= number_format($loc['total_items'] ?? 0) ?></span>
                <span class="text-xs text-slate-400 font-medium">Pcs Stok</span>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Sales Today Card -->
    <div class="bg-indigo-600 p-5 rounded-2xl shadow-sm text-white flex flex-col justify-between">
        <div>
            <span class="text-[10px] tracking-wider uppercase font-extrabold text-indigo-200">PENJUALAN HARI INI</span>
            <h3 class="font-semibold text-indigo-100 text-sm mt-0.5">Gerai Penjualan Utama</h3>
        </div>
        <div class="mt-4 flex items-baseline justify-between">
            <span class="text-2xl font-bold">Rp <?= number_format($todaySales['total_revenue'], 0, ',', '.') ?></span>
            <span class="text-xs text-indigo-200 font-medium"><?= $todaySales['total_tx'] ?> Transaksi</span>
        </div>
    </div>
</div>

<!-- Visual Analytics Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Chart 1: Tren Penjualan -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Tren Penjualan Toko</h3>
                <p class="text-xs text-slate-500">Estimasi akumulasi pendapatan bulanan & mingguan.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">Otomatis Terupdate</span>
        </div>
        <div class="h-64">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Proporsi Stok Multi-Lokasi -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
        <div>
            <h3 class="font-bold text-slate-800 text-base mb-1">Sebaran Stok Lokasi</h3>
            <p class="text-xs text-slate-500 mb-4">Proporsi total unit persediaan di 3 titik lokasi.</p>
            <div class="h-48 flex items-center justify-center">
                <canvas id="stockDistributionChart"></canvas>
            </div>
        </div>
        <div class="text-center text-xs text-slate-400 mt-2">
            Penyebaran ideal: 60% Gudang 1, 30% Gudang 2, 10% Gerai
        </div>
    </div>
</div>

<!-- Main Data Grid (2 Columns) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Reorder Point Alert Table (2 cols) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden h-fit">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Peringatan Stok Kritis (Reorder Point Alert)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar produk yang menyentuh batas minimal safety stock.</p>
            </div>
            <a href="<?= base_url('purchase/create') ?>" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                + Restock Sekarang
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-3.5">SKU</th>
                        <th class="px-6 py-3.5">Nama Produk</th>
                        <th class="px-6 py-3.5 text-center">Batas Min (ROP)</th>
                        <th class="px-6 py-3.5 text-center">Stok Riil</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($lowStockAlerts)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm">
                                🎉 Semua stok dalam kondisi aman di atas batas minimum.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($lowStockAlerts as $alert): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-mono text-xs text-slate-500"><?= $alert['sku'] ?></td>
                                <td class="px-6 py-4 font-semibold text-slate-800"><?= $alert['product_name'] ?></td>
                                <td class="px-6 py-4 text-center font-medium text-slate-600"><?= $alert['min_stock'] ?></td>
                                <td class="px-6 py-4 text-center font-bold text-rose-600"><?= $alert['current_qty'] ?></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        Perlu Restock
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Top Products & Recent Transactions Widget -->
    <div class="space-y-6">
        <!-- Widget 1: Fast-Moving Products -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h3 class="font-bold text-slate-800 text-base mb-1">Produk Terlaris (Fast-Moving)</h3>
            <p class="text-xs text-slate-500 mb-4">Acuan manajemen untuk menambah alokasi kuantitas pemesanan.</p>

            <div class="space-y-3.5">
                <?php if (empty($topProducts)): ?>
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada data penjualan.</p>
                <?php else: ?>
                    <?php foreach ($topProducts as $idx => $tp): ?>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="w-5 h-5 rounded-md bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-[10px]">
                                    <?= $idx + 1 ?>
                                </span>
                                <span class="font-medium text-slate-700"><?= $tp['name'] ?></span>
                            </div>
                            <span class="font-bold text-slate-900"><?= $tp['total_sold'] ?> <?= $tp['unit'] ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Widget 2: Recent Transactions -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h3 class="font-bold text-slate-800 text-base mb-1">Transaksi Kasir Terbaru</h3>
            <p class="text-xs text-slate-500 mb-4">Aktivitas struk penjualan real-time di Gerai.</p>

            <div class="space-y-3">
                <?php if (empty($recentSales)): ?>
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada transaksi terjadi.</p>
                <?php else: ?>
                    <?php foreach ($recentSales as $rs): ?>
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                            <div>
                                <div class="font-mono font-medium text-slate-700"><?= $rs['invoice_number'] ?></div>
                                <div class="text-[10px] text-slate-400"><?= date('d/m/Y H:i', strtotime($rs['created_at'])) ?></div>
                            </div>
                            <span class="font-bold text-emerald-600">Rp <?= number_format($rs['total_amount'], 0, ',', '.') ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart JS Initialization Script -->
<script>
    // 1. Line Chart: Sales Trend
    // 1. Line Chart: Sales Trend (100% Real Database)
const ctxSales = document.getElementById('salesTrendChart').getContext('2d');
new Chart(ctxSales, {
    type: 'line',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [{
            label: 'Penjualan (Rp)',
            data: <?= json_encode($chartData) ?>,
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79, 70, 229, 0.08)',
            fill: true,
            tension: 0.35,
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#4f46e5'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});

    // 2. Doughnut Chart: Stock Distribution
    const ctxStock = document.getElementById('stockDistributionChart').getContext('2d');
    new Chart(ctxStock, {
        type: 'doughnut',
        data: {
            labels: [
                <?php foreach ($stocksByLocation as $sl): ?>
                    '<?= $sl['location_name'] ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                data: [
                    <?php foreach ($stocksByLocation as $sl): ?>
                        <?= $sl['total_items'] ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: ['#0f172a', '#475569', '#4f46e5'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
            },
            cutout: '70%'
        }
    });
</script>

<?= $this->endSection() ?>