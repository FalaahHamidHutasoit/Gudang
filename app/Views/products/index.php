<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="font-bold text-slate-800 text-base">Katalog Master Produk</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola daftar barang, satuan, batas minimum safety stock (ROP), dan harga jual.</p>
        </div>
        <a href="<?= base_url('products/create') ?>" class="px-4 py-2.5 text-xs font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition w-fit">
            + Tambah Produk Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider font-semibold">
                <tr>
                    <th class="px-6 py-3.5">SKU</th>
                    <th class="px-6 py-3.5">Nama Produk</th>
                    <th class="px-6 py-3.5">Satuan</th>
                    <th class="px-6 py-3.5 text-center">Batas Min (ROP)</th>
                    <th class="px-6 py-3.5 text-right">Harga Jual</th>
                    <th class="px-6 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada produk terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-500"><?= $p['sku'] ?></td>
                            <td class="px-6 py-4 font-semibold text-slate-800"><?= $p['name'] ?></td>
                            <td class="px-6 py-4 text-xs text-slate-500"><?= $p['unit'] ?></td>
                            <td class="px-6 py-4 text-center font-bold text-amber-600"><?= $p['min_stock'] ?> Pcs</td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-800">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('products/edit/' . $p['id']) ?>" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('products/delete/' . $p['id']) ?>" onclick="return confirm('Yakin ingin menghapus produk ini? Semua data stok terkait akan terhapus.')" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>