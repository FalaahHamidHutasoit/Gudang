<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="font-bold text-slate-800 text-base">Matriks Persediaan Barang (Multi-Lokasi)</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pantau jumlah stok riil di Gudang 1, Gudang 2, dan Gerai Penjualan secara terpusat.</p>
        </div>
        <a href="<?= base_url('inventory/transfer') ?>" class="px-4 py-2.5 text-xs font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition w-fit">
            ⇄ Mutasi / Pemindahan Stok
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider font-semibold">
                <tr>
                    <th class="px-6 py-3.5">SKU</th>
                    <th class="px-6 py-3.5">Nama Produk</th>
                    <th class="px-6 py-3.5 text-center">Batas Min (ROP)</th>
                    <?php foreach ($locations as $loc): ?>
                        <th class="px-6 py-3.5 text-center bg-slate-100/50"><?= $loc['name'] ?></th>
                    <?php endforeach; ?>
                    <th class="px-6 py-3.5 text-center">Total Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($products as $p): ?>
                    <?php $totalQty = 0; ?>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-mono text-xs text-slate-500 font-medium"><?= $p['sku'] ?></td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            <?= $p['name'] ?>
                            <span class="block text-xs font-normal text-slate-400">Rp <?= number_format($p['price'], 0, ',', '.') ?> / <?= $p['unit'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-slate-500"><?= $p['min_stock'] ?></td>
                        
                        <?php foreach ($locations as $loc): ?>
                            <?php 
                                $qty = $stocks[$p['id']][$loc['id']] ?? 0;
                                $totalQty += $qty;
                                $isGerai = ($loc['type'] === 'gerai');
                                $isLowStock = ($isGerai && $qty <= $p['min_stock']);
                            ?>
                            <td class="px-6 py-4 text-center font-bold bg-slate-50/30 <?= $isLowStock ? 'text-rose-600' : 'text-slate-700' ?>">
                                <?= $qty ?>
                                <?php if ($isLowStock): ?>
                                    <span class="block text-[10px] font-normal text-rose-500">Kritis!</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>

                        <td class="px-6 py-4 text-center font-extrabold text-indigo-600 bg-indigo-50/30">
                            <?= $totalQty ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>