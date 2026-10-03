<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm">
    <div class="mb-6">
        <h3 class="text-base font-bold text-slate-800">Edit Data Produk</h3>
        <p class="text-xs text-slate-500">Ubah nama, harga, atau batas minimum safety stock.</p>
    </div>

    <form action="<?= base_url('products/update/' . $product['id']) ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Kode SKU (Readonly)</label>
                <input type="text" value="<?= $product['sku'] ?>" readonly class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm font-mono cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Satuan Produk</label>
                <input type="text" name="unit" value="<?= $product['unit'] ?>" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Produk</label>
            <input type="text" name="name" value="<?= $product['name'] ?>" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Batas Minimum Stok (ROP)</label>
                <input type="number" name="min_stock" min="1" value="<?= $product['min_stock'] ?>" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Harga Jual (Rp)</label>
                <input type="number" name="price" step="500" value="<?= (int)$product['price'] ?>" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="<?= base_url('products') ?>" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>