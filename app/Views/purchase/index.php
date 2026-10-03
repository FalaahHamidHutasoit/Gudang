<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-800 text-base">Daftar Purchase Order (PO)</h3>
            <p class="text-xs text-slate-500">Kelola pemesanan ulang ke supplier dan penerimaan barang di gudang.</p>
        </div>
        <a href="<?= base_url('purchase/create') ?>" class="px-4 py-2.5 text-xs font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">
            + Buat PO Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider font-semibold">
                <tr>
                    <th class="px-6 py-3.5">No. PO</th>
                    <th class="px-6 py-3.5">Supplier</th>
                    <th class="px-6 py-3.5">Gudang Tujuan</th>
                    <th class="px-6 py-3.5">Total Value</th>
                    <th class="px-6 py-3.5 text-center">Status</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada dokumen Purchase Order.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500 font-medium"><?= $o['po_number'] ?></td>
                            <td class="px-6 py-4 font-medium text-slate-800"><?= $o['supplier_name'] ?></td>
                            <td class="px-6 py-4 text-xs text-slate-500"><?= $o['warehouse_name'] ?></td>
                            <td class="px-6 py-4 font-medium text-slate-800">Rp <?= number_format($o['total_amount'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($o['status'] === 'pending'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu Barang
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Barang Diterima
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if ($o['status'] === 'pending'): ?>
                                    <a href="<?= base_url('purchase/receive/' . $o['id']) ?>" onclick="return confirm('Apakah barang dari supplier sudah sampai di gudang?')" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                        Konfirmasi Terima
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400 font-medium">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>