<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    @media print {
        body * { visibility: hidden; }
        #printableArea, #printableArea * { visibility: visible; }
        #printableArea { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
</style>

<div class="space-y-6">
    <!-- Filter Box (Hidden on Print) -->
    <div class="no-print bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-end justify-between gap-4">
        <form action="<?= base_url('reports') ?>" method="GET" class="flex flex-wrap items-end gap-3 flex-1">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?= $startDate ?>" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="<?= $endDate ?>" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                Filter Laporan
            </button>
        </form>

        <button onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition flex items-center gap-2">
            🖨️ Cetak Laporan (PDF)
        </button>
    </div>

    <!-- Printable Report Container -->
    <div id="printableArea" class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
        <!-- Header Laporan -->
        <div class="border-b border-slate-200 pb-4 flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-slate-900">TOKO RITEL MAKMUR</h2>
                <p class="text-xs text-slate-500">Laporan Rekapitulasi Penjualan Gerai</p>
            </div>
            <div class="text-right text-xs text-slate-500">
                <p>Periode: <strong class="text-slate-800"><?= date('d/m/Y', strtotime($startDate)) ?></strong> s/d <strong class="text-slate-800"><?= date('d/m/Y', strtotime($endDate)) ?></strong></p>
                <p>Dicetak Tanggal: <?= date('d/m/Y H:i') ?></p>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-indigo-50/50 border border-indigo-100">
                <span class="text-xs font-medium text-indigo-700 uppercase">Total Transaksi Selesai</span>
                <div class="text-2xl font-bold text-indigo-900 mt-1"><?= number_format($summary['total_tx']) ?> Transaksi</div>
            </div>
            <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100">
                <span class="text-xs font-medium text-emerald-700 uppercase">Total Omzet Penjualan</span>
                <div class="text-2xl font-bold text-emerald-900 mt-1">Rp <?= number_format($summary['grand_total'], 0, ',', '.') ?></div>
            </div>
        </div>

        <!-- Tabel Detail Transaksi -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-y border-slate-200 font-semibold text-slate-700 uppercase">
                    <tr>
                        <th class="px-4 py-3">No. Struk (Invoice)</th>
                        <th class="px-4 py-3">Lokasi Transaksi</th>
                        <th class="px-4 py-3">Waktu Transaksi</th>
                        <th class="px-4 py-3 text-right">Total Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($sales)): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-400">Tidak ada transaksi penjualan pada periode ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sales as $s): ?>
                            <tr>
                                <td class="px-4 py-3 font-mono font-bold text-slate-800"><?= $s['invoice_number'] ?></td>
                                <td class="px-4 py-3 text-slate-600"><?= $s['location_name'] ?></td>
                                <td class="px-4 py-3 text-slate-500"><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900">Rp <?= number_format($s['total_amount'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>