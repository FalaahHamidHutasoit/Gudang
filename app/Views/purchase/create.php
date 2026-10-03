<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm">
    <div class="mb-6">
        <h3 class="text-base font-bold text-slate-800">Buat Purchase Order (PO)</h3>
        <p class="text-xs text-slate-500">Isi detail pesanan ke supplier. Barang akan masuk ke gudang tujuan saat dikonfirmasi.</p>
    </div>

    <form action="<?= base_url('purchase/store') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Supplier</label>
            <input type="text" name="supplier_name" placeholder="PT. Distributor Jaya" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Gudang Tujuan Penerimaan</label>
            <select name="target_location_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Pilih Gudang --</option>
                <?php foreach ($warehouses as $w): ?>
                    <option value="<?= $w['id'] ?>"><?= $w['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Produk</label>
                <select name="product_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['name'] ?> (<?= $p['sku'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Jumlah (Qty)</label>
                <input type="number" name="qty" min="1" value="10" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Harga Beli per Unit (Rp)</label>
                <input type="number" name="unit_cost" step="500" placeholder="50000" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="<?= base_url('purchase') ?>" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                Terbitkan PO
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>