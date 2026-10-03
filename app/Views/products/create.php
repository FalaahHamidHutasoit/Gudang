<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm">
    <div class="mb-6">
        <h3 class="text-base font-bold text-slate-800">Tambah Produk Baru</h3>
        <p class="text-xs text-slate-500">Produk yang dibuat akan langsung tercatat otomatis di sistem persediaan multi-gudang.</p>
    </div>

    <form action="<?= base_url('products/store') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Kode SKU</label>
                <input type="text" name="sku" placeholder="BRG-009" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 uppercase font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Satuan Produk</label>
                <input type="text" name="unit" placeholder="Pcs / Pouch / Kardus" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Produk</label>
            <input type="text" name="name" placeholder="Kecap Manis Refill 520ml" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Batas Minimum Stok (ROP)</label>
                <input type="number" name="min_stock" min="1" value="10" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="text-[10px] text-slate-400 mt-1 block">Toleransi stok minimum sebelum alert menyala.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Harga Jual (Rp)</label>
                <input type="number" name="price" step="500" placeholder="25000" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="<?= base_url('products') ?>" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                Simpan Produk
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>