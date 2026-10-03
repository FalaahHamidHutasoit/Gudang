<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Transfer (Kiri) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm h-fit">
        <h3 class="font-bold text-slate-800 text-base mb-1">Form Mutasi Barang</h3>
        <p class="text-xs text-slate-500 mb-6">Pindahkan persediaan antar Gudang 1, Gudang 2, atau Gerai Penjualan.</p>

        <form action="<?= base_url('inventory/transfer/process') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Lokasi Asal</label>
                <select name="from_location_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Dari Lokasi --</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= $loc['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Lokasi Tujuan</label>
                <select name="to_location_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Ke Lokasi --</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= $loc['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Produk</label>
                <select name="product_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['name'] ?> (<?= $p['sku'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Jumlah (Qty)</label>
                <input type="number" name="qty" min="1" value="1" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full mt-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                Eksekusi Pemindahan Stok
            </button>
        </form>
    </div>

    <!-- Tabel Riwayat Mutasi (Kanan) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Riwayat Mutasi Stok</h3>
            <p class="text-xs text-slate-500">Daftar transaksi mutasi fisik barang yang telah terekam sistem.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-3">Kode</th>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Dari</th>
                        <th class="px-6 py-3">Ke</th>
                        <th class="px-6 py-3 text-center">Qty</th>
                        <th class="px-6 py-3 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($transfers)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-slate-400 text-xs">Belum ada riwayat mutasi stok.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transfers as $t): ?>
                            <tr class="hover:bg-slate-50/80 transition text-xs">
                                <td class="px-6 py-3.5 font-mono text-slate-500"><?= $t['transfer_code'] ?></td>
                                <td class="px-6 py-3.5 font-medium text-slate-800"><?= $t['product_name'] ?></td>
                                <td class="px-6 py-3.5 text-slate-500"><?= $t['from_location'] ?></td>
                                <td class="px-6 py-3.5 text-slate-800 font-medium"><?= $t['to_location'] ?></td>
                                <td class="px-6 py-3.5 text-center font-bold text-indigo-600">+<?= $t['qty'] ?></td>
                                <td class="px-6 py-3.5 text-right text-slate-400"><?= date('d/m/H:i', strtotime($t['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>