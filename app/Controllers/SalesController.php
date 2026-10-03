<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SalesController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function create()
    {
        $geraiId = 3; // Gerai Utama

        $products = $this->db->query("
            SELECT p.id, p.sku, p.name, p.unit, p.price, COALESCE(s.qty, 0) as current_stock
            FROM products p
            LEFT JOIN stocks s ON p.id = s.product_id AND s.location_id = ?
            ORDER BY p.name ASC
        ", [$geraiId])->getResultArray();

        return view('sales/create', [
            'title'        => 'Kasir POS - Toko Ritel Makmur',
            'page_heading' => 'Mesin Kasir POS (Gerai Penjualan Utama)',
            'products'     => $products
        ]);
    }

    public function store()
    {
        $geraiId    = 3; // Fixed Gerai Utama
        $cartData   = $this->request->getPost('cart_json');
        $cashAmount = (float)$this->request->getPost('cash_amount');

        $cart = json_decode($cartData, true);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja masih kosong!');
        }

        // 1. Validasi Stok
        foreach ($cart as $item) {
            $stockCheck = $this->db->query("
                SELECT qty FROM stocks WHERE location_id = ? AND product_id = ?
            ", [$geraiId, $item['id']])->getRowArray();

            if (!$stockCheck || $stockCheck['qty'] < $item['qty']) {
                return redirect()->back()->with('error', "Gagal Transaksi! Stok '{$item['name']}' di Gerai tidak mencukupi.");
            }
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += ($item['price'] * $item['qty']);
        }

        if ($cashAmount < $totalAmount) {
            return redirect()->back()->with('error', 'Gagal Transaksi! Nominal uang tunai kurang dari total belanja.');
        }

        // Start Transaction
        $this->db->transStart();

        // 2. Simpan Header Penjualan
        $invoiceNo = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
        $this->db->table('sales')->insert([
            'invoice_number' => $invoiceNo,
            'location_id'    => $geraiId,
            'total_amount'   => $totalAmount,
            'created_at'     => date('Y-m-d H:i:s')
        ]);
        $saleId = $this->db->insertID();

        // 3. Simpan Detail Item & Potong Stok Gerai
        $printedItems = [];
        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['qty'];

            $this->db->table('sale_items')->insert([
                'sale_id'    => $saleId,
                'product_id' => $item['id'],
                'qty'        => $item['qty'],
                'price'      => $item['price'],
                'subtotal'   => $subtotal
            ]);

            // Potong Stok
            $this->db->query("
                UPDATE stocks SET qty = qty - ? WHERE location_id = ? AND product_id = ?
            ", [$item['qty'], $geraiId, $item['id']]);

            $printedItems[] = [
                'name'     => $item['name'],
                'qty'      => $item['qty'],
                'price'    => $item['price'],
                'subtotal' => $subtotal
            ];
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses transaksi.');
        }

        $changeAmount = $cashAmount - $totalAmount;

        // Data Struk untuk Pop-up Invoice
        $invoiceData = [
            'invoice_number' => $invoiceNo,
            'created_at'     => date('d/m/Y H:i'),
            'cashier_name'   => session()->get('fullname') ?? 'Siti Rahma (Kasir)',
            'items'          => $printedItems,
            'total_amount'   => $totalAmount,
            'cash_amount'    => $cashAmount,
            'change_amount'  => $changeAmount
        ];

        return redirect()->to('sales/create')
                         ->with('invoice_data', $invoiceData)
                         ->with('success', "Transaksi $invoiceNo Berhasil Diselesaikan!");
    }

    // Halaman Riwayat Transaksi khusus Gerai
public function history()
{
    $geraiId = 3; // Gerai Utama

    $sales = $this->db->query("
        SELECT s.id, s.invoice_number, s.total_amount, s.created_at, l.name as location_name
        FROM sales s
        JOIN locations l ON s.location_id = l.id
        WHERE s.location_id = ?
        ORDER BY s.created_at DESC
        LIMIT 50
    ", [$geraiId])->getResultArray();

    return view('sales/history', [
        'title'        => 'Riwayat Transaksi - Toko Ritel Makmur',
        'page_heading' => 'Riwayat Transaksi Kasir Gerai',
        'sales'        => $sales
    ]);
}

// Fitur Cetak Ulang Struk dari Riwayat
public function reprint($id)
{
    $sale = $this->db->table('sales')->where('id', $id)->get()->getRowArray();
    if (!$sale) {
        return redirect()->to('sales/history')->with('error', 'Transaksi tidak ditemukan.');
    }

    $items = $this->db->query("
        SELECT p.name, si.qty, si.price, si.subtotal
        FROM sale_items si
        JOIN products p ON si.product_id = p.id
        WHERE si.sale_id = ?
    ", [$id])->getResultArray();

    $invoiceData = [
        'invoice_number' => $sale['invoice_number'],
        'created_at'     => date('d/m/Y H:i', strtotime($sale['created_at'])),
        'cashier_name'   => session()->get('fullname') ?? 'Kasir Gerai',
        'items'          => $items,
        'total_amount'   => $sale['total_amount'],
        'cash_amount'    => $sale['total_amount'],
        'change_amount'  => 0
    ];

    return redirect()->to('sales/history')
                     ->with('invoice_data', $invoiceData)
                     ->with('success', "Menampilkan struk untuk invoice {$sale['invoice_number']}");
}

}