<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    @media print {
        body * { visibility: hidden; }
        #invoiceReceiptModal, #invoiceReceiptModal * { visibility: visible; }
        #invoiceReceiptModal {
            position: absolute; left: 0; top: 0; width: 100%;
            background: white !important; box-shadow: none !important;
        }
        .no-print { display: none !important; }
    }
</style>

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-800 text-base">Riwayat Transaksi Kasir</h3>
            <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi penjualan yang telah diproses di Gerai Utama.</p>
        </div>
        <a href="<?= base_url('sales/create') ?>" class="px-4 py-2 text-xs font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">
            + Input Transaksi Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider font-semibold">
                <tr>
                    <th class="px-6 py-3.5">No. Struk (Invoice)</th>
                    <th class="px-6 py-3.5">Waktu Transaksi</th>
                    <th class="px-6 py-3.5">Lokasi</th>
                    <th class="px-6 py-3.5 text-right">Total Transaksi</th>
                    <th class="px-6 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($sales)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">Belum ada riwayat transaksi.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($sales as $s): ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-xs text-slate-800"><?= $s['invoice_number'] ?></td>
                            <td class="px-6 py-4 text-xs text-slate-500"><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                            <td class="px-6 py-4 text-xs text-slate-600"><?= $s['location_name'] ?></td>
                            <td class="px-6 py-4 text-right font-extrabold text-indigo-600">Rp <?= number_format($s['total_amount'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?= base_url('sales/reprint/' . $s['id']) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition">
                                    🖨️ Cetak Struk
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Struk / Receipt Popup -->
<?php if ($inv = session()->getFlashdata('invoice_data')): ?>
<div id="invoiceModalOverlay" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div id="invoiceReceiptModal" class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 font-mono text-slate-800 text-xs leading-relaxed space-y-4">
        
        <div class="text-center space-y-1 pb-3 border-b border-dashed border-slate-300">
            <h3 class="font-bold text-sm tracking-wide text-slate-900 font-sans">TOKO RITEL MAKMUR</h3>
            <p class="text-[10px] text-slate-500 font-sans">Jl. Raya Pemuda No. 88, Jakarta</p>
            <p class="text-[10px] text-slate-500 font-sans">Gerai Penjualan Utama</p>
        </div>

        <div class="space-y-1 text-[11px] text-slate-600 border-b border-dashed border-slate-300 pb-3">
            <div class="flex justify-between">
                <span>No. Struk:</span>
                <span class="font-bold text-slate-900"><?= $inv['invoice_number'] ?></span>
            </div>
            <div class="flex justify-between">
                <span>Waktu:</span>
                <span><?= $inv['created_at'] ?></span>
            </div>
            <div class="flex justify-between">
                <span>Kasir:</span>
                <span><?= $inv['cashier_name'] ?></span>
            </div>
        </div>

        <div class="space-y-2 border-b border-dashed border-slate-300 pb-3">
            <?php foreach ($inv['items'] as $item): ?>
                <div>
                    <div class="font-bold text-slate-800"><?= $item['name'] ?></div>
                    <div class="flex justify-between text-slate-500 text-[11px]">
                        <span><?= $item['qty'] ?> x Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                        <span class="font-semibold text-slate-900">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="space-y-1.5 text-[11px] pt-1">
            <div class="flex justify-between font-extrabold text-sm text-slate-900">
                <span>TOTAL:</span>
                <span>Rp <?= number_format($inv['total_amount'], 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="text-center text-[10px] text-slate-400 pt-3 border-t border-dashed border-slate-300 space-y-1 font-sans">
            <p class="font-semibold text-slate-600">Terima Kasih Telah Berbelanja!</p>
        </div>

        <div class="no-print pt-2 flex items-center gap-2">
            <button onclick="window.print()" class="flex-1 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-sans font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                Cetak Struk
            </button>
            <button onclick="document.getElementById('invoiceModalOverlay').remove()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-sans font-bold text-xs transition">
                Tutup
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>