<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login - Toko Ritel Makmur' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-md bg-white rounded-3xl p-8 shadow-2xl border border-slate-800">
        <!-- Brand Logo Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto mb-3 shadow-md shadow-indigo-600/30">
                RM
            </div>
            <h1 class="text-xl font-bold text-slate-900">Toko Ritel Makmur</h1>
            <p class="text-xs text-slate-500 mt-1">Sistem Informasi Persediaan & POS Multi-Lokasi</p>
        </div>

        <!-- Flash Message Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form action="<?= base_url('login/process') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required autofocus class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password" placeholder="••••••••" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-600/20 transition mt-2">
                Masuk ke Sistem
            </button>
        </form>

        <!-- Quick Demo Account Info Box -->
        <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-500">
            <span class="font-bold text-slate-700 block mb-2">💡 Akun Demo Uji Coba Dosen:</span>
            <div class="grid grid-cols-2 gap-2 text-[11px] font-mono">
                <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                    <span class="font-bold text-slate-800">Admin/Owner</span><br>
                    user: admin<br>pass: password123
                </div>
                <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                    <span class="font-bold text-slate-800">Kasir Gerai</span><br>
                    user: kasir<br>pass: password123
                </div>
                <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                    <span class="font-bold text-slate-800">Staf Gudang</span><br>
                    user: gudang<br>pass: password123
                </div>
                <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                    <span class="font-bold text-slate-800">Pembelian</span><br>
                    user: pembelian<br>pass: password123
                </div>
            </div>
        </div>
    </div>

</body>
</html>