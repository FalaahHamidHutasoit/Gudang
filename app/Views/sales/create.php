<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- CSS Khusus Cetak Struk / Receipt Print -->
<style>
    @media print {
        body * { visibility: hidden; }
        #invoiceReceiptModal, #invoiceReceiptModal * { visibility: visible; }
        #invoiceReceiptModal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
            box-shadow: none !important;
        }
        .no-print { display: none !important; }
    }
</style>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Kolom Kiri: Katalog Produk (7 Cols) -->
    <div class="lg:col-span-7 space-y-4">
        <!-- Search & Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between gap-3">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="searchProduct" placeholder="Cari nama produk atau SKU..." onkeyup="filterProducts()" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
            <span class="text-xs text-slate-400 font-medium whitespace-nowrap">Klik kartu untuk tambah</span>
        </div>

        <!-- Product Cards Grid -->
        <div id="productGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <?php foreach ($products as$p): ?>
                <div onclick="addToCart(<?= $p['id'] ?>, '<?= addslashes($p['name']) ?>', <?= $p['price'] ?>, <?=$p['current_stock'] ?>)" 
                     class="product-card cursor-pointer bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-indigo-500 hover:shadow-md transition flex flex-col justify-between group"
                     data-name="<?= strtolower($p['name']) ?>" data-sku="<?= strtolower($p['sku']) ?>">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-mono text-slate-400 uppercase"><?= $p['sku'] ?></span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $p['current_stock'] > 0 ? 'bg-slate-100 text-slate-600' : 'bg-rose-50 text-rose-600' ?>">
                                Stok: <?= $p['current_stock'] ?>
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-xs line-clamp-2 group-hover:text-indigo-600 transition"><?= $p['name'] ?></h4>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="font-extrabold text-indigo-600 text-sm">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white flex items-center justify-center font-bold text-xs transition">+</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Kolom Kanan: Struk / Keranjang Belanja (5 Cols) -->
    <div class="lg:col-span-5">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden sticky top-8">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-900 text-white">
                <div>
                    <h3 class="font-bold text-sm">Struk Kasir Penjualan</h3>
                    <p class="text-[11px] text-slate-400">Gerai Utama • Toko Ritel Makmur</p>
                </div>
                <button onclick="clearCart()" class="text-xs text-rose-400 hover:text-rose-300 font-semibold transition">
                    Bersihkan
                </button>
            </div>

            <!-- Cart Items Container -->
            <div id="cartList" class="p-4 space-y-3 max-h-[320px] overflow-y-auto divide-y divide-slate-100">
                <div id="emptyCartState" class="py-12 text-center text-slate-400 text-xs">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Keranjang masih kosong.<br>Klik produk di sebelah kiri untuk menambah.
                </div>
            </div>

            <!-- Payment Calculation Panel -->
            <form action="<?= base_url('sales/store') ?>" method="POST" id="checkoutForm" onsubmit="return validateCheckout()" class="p-5 bg-slate-50 border-t border-slate-100 space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="cart_json" id="cartJsonInput">

                <!-- Summary Breakdown -->
                <div class="space-y-1.5 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Total Item Belanja</span>
                        <span id="totalItemsText" class="font-bold text-slate-800">0 Pcs</span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                        <span>Grand Total</span>
                        <span id="grandTotalText" class="text-indigo-600">Rp 0</span>
                    </div>
                </div>

                <!-- Cash & Change Input -->
                <div class="pt-2 border-t border-slate-200 space-y-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nominal Uang Tunai (Rp)</label>
                        <input type="number" name="cash_amount" id="cashInput" placeholder="0" oninput="calculateChange()" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-between items-center text-xs p-3 rounded-xl bg-indigo-50/60 border border-indigo-100">
                        <span class="font-semibold text-indigo-900">Kembalian:</span>
                        <span id="changeText" class="font-bold text-indigo-700 text-sm">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="btnCheckout" disabled class="w-full py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-slate-300 text-slate-500 cursor-not-allowed transition shadow-sm">
                    Bayar & Selesaikan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- POP-UP MODAL INVOICE STRUK PENJUALAN -->
<!-- ========================================================= -->
<?php if ($inv = session()->getFlashdata('invoice_data')): ?>
<div id="invoiceModalOverlay" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div id="invoiceReceiptModal" class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 font-mono text-slate-800 text-xs leading-relaxed space-y-4">
        
        <!-- Header Struk -->
        <div class="text-center space-y-1 pb-3 border-b border-dashed border-slate-300">
            <h3 class="font-bold text-sm tracking-wide text-slate-900 font-sans">TOKO RITEL MAKMUR</h3>
            <p class="text-[10px] text-slate-500 font-sans">Jl. Raya Pemuda No. 88, Jakarta</p>
            <p class="text-[10px] text-slate-500 font-sans">Gerai Penjualan Utama</p>
        </div>

        <!-- Meta Transaksi -->
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

        <!-- Daftar Barang Belanja -->
        <div class="space-y-2 border-b border-dashed border-slate-300 pb-3">
            <?php foreach ($inv['items'] as$item): ?>
                <div>
                    <div class="font-bold text-slate-800"><?= $item['name'] ?></div>
                    <div class="flex justify-between text-slate-500 text-[11px]">
                        <span><?= $item['qty'] ?> x Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                        <span class="font-semibold text-slate-900">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Rincian Pembayaran -->
        <div class="space-y-1.5 text-[11px] pt-1">
            <div class="flex justify-between font-extrabold text-sm text-slate-900">
                <span>TOTAL:</span>
                <span>Rp <?= number_format($inv['total_amount'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>TUNAI:</span>
                <span>Rp <?= number_format($inv['cash_amount'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between text-slate-900 font-bold">
                <span>KEMBALI:</span>
                <span>Rp <?= number_format($inv['change_amount'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Footer Struk -->
        <div class="text-center text-[10px] text-slate-400 pt-3 border-t border-dashed border-slate-300 space-y-1 font-sans">
            <p class="font-semibold text-slate-600">Terima Kasih Telah Berbelanja!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>

        <!-- Action Buttons Modal (Tidak ikut tercetak) -->
        <div class="no-print pt-2 flex items-center gap-2">
            <button onclick="window.print()" class="flex-1 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-sans font-bold text-xs shadow-md shadow-indigo-600/20 transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Struk
            </button>
            <button onclick="closeInvoiceModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-sans font-bold text-xs transition">
                Selesai
            </button>
        </div>

    </div>
</div>

<script>
    function closeInvoiceModal() {
        const overlay = document.getElementById('invoiceModalOverlay');
        if (overlay) {
            overlay.remove();
        }
    }
</script>
<?php endif; ?>

<!-- JavaScript Engine POS -->
<script>
    let cart = [];

    function addToCart(id, name, price, stock) {
        if (stock <= 0) {
            alert(`Stok '${name}' di Gerai sedang habis!`);
            return;
        }

        let existingIndex = cart.findIndex(item => item.id === id);

        if (existingIndex > -1) {
            if (cart[existingIndex].qty + 1 > stock) {
                alert(`Jumlah melebihi sisa stok riil gerai (${stock} Pcs)!`);
                return;
            }
            cart[existingIndex].qty += 1;
        } else {
            cart.push({ id, name, price, stock, qty: 1 });
        }

        renderCart();
    }

    function updateQty(index, delta) {
        let newQty = cart[index].qty + delta;

        if (newQty <= 0) {
            cart.splice(index, 1);
        } else if (newQty > cart[index].stock) {
            alert(`Stok maksimal gerai hanya ${cart[index].stock} Pcs!`);
        } else {
            cart[index].qty = newQty;
        }

        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        const cartList = document.getElementById('cartList');

        if (cart.length === 0) {
            cartList.innerHTML = `<div id="emptyCartState" class="py-12 text-center text-slate-400 text-xs"><svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>Keranjang masih kosong.<br>Klik produk di sebelah kiri untuk menambah.</div>`;
            document.getElementById('totalItemsText').innerText = '0 Pcs';
            document.getElementById('grandTotalText').innerText = 'Rp 0';
            document.getElementById('cartJsonInput').value = '';
            document.getElementById('btnCheckout').disabled = true;
            document.getElementById('btnCheckout').className = 'w-full py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-slate-300 text-slate-500 cursor-not-allowed transition shadow-sm';
            calculateChange();
            return;
        }

        let html = '';
        let totalItems = 0;
        let grandTotal = 0;

        cart.forEach((item, index) => {
            let subtotal = item.price * item.qty;
            totalItems += item.qty;
            grandTotal += subtotal;

            html += `
                <div class="flex items-center justify-between pt-2">
                    <div class="flex-1 pr-2">
                        <h5 class="font-semibold text-slate-800 text-xs line-clamp-1">${item.name}</h5>
                        <span class="text-[10px] text-slate-400">Rp ${formatRupiah(item.price)} x ${item.qty}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                            <button type="button" onclick="updateQty(${index}, -1)" class="px-2 py-0.5 text-xs font-bold text-slate-600 hover:bg-slate-200">-</button>
                            <span class="px-2 text-xs font-bold text-slate-800">${item.qty}</span>
                            <button type="button" onclick="updateQty(${index}, 1)" class="px-2 py-0.5 text-xs font-bold text-slate-600 hover:bg-slate-200">+</button>
                        </div>
                        <span class="font-bold text-xs text-slate-800 w-16 text-right">Rp ${formatRupiah(subtotal)}</span>
                    </div>
                </div>
            `;
        });

        cartList.innerHTML = html;
        document.getElementById('totalItemsText').innerText = `${totalItems} Pcs`;
        document.getElementById('grandTotalText').innerText = `Rp ${formatRupiah(grandTotal)}`;
        document.getElementById('cartJsonInput').value = JSON.stringify(cart);

        const btnCheckout = document.getElementById('btnCheckout');
        btnCheckout.disabled = false;
        btnCheckout.className = 'w-full py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-600/20 transition';

        calculateChange();
    }

    function calculateChange() {
        let grandTotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let cash = parseFloat(document.getElementById('cashInput').value) || 0;
        let change = cash - grandTotal;

        const changeText = document.getElementById('changeText');
        if (cash > 0 && change >= 0) {
            changeText.innerText = `Rp ${formatRupiah(change)}`;
            changeText.className = 'font-bold text-emerald-600 text-sm';
        } else if (cash > 0 && change < 0) {
            changeText.innerText = `Kurang Rp ${formatRupiah(Math.abs(change))}`;
            changeText.className = 'font-bold text-rose-600 text-sm';
        } else {
            changeText.innerText = 'Rp 0';
            changeText.className = 'font-bold text-indigo-700 text-sm';
        }
    }

    function validateCheckout() {
        let grandTotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let cash = parseFloat(document.getElementById('cashInput').value) || 0;

        if (cart.length === 0) {
            alert('Keranjang belanja kosong!');
            return false;
        }

        if (cash < grandTotal) {
            alert('Uang tunai kurang dari total belanja!');
            return false;
        }

        return true;
    }

    function filterProducts() {
        let input = document.getElementById('searchProduct').value.toLowerCase();
        let cards = document.getElementsByClassName('product-card');

        for (let card of cards) {
            let name = card.getAttribute('data-name');
            let sku = card.getAttribute('data-sku');

            if (name.includes(input) || sku.includes(input)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        }
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
</script>

<?= $this->endSection() ?>