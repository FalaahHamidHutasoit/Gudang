<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TransferController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $locations = $this->db->table('locations')->get()->getResultArray();
        $products  = $this->db->table('products')->get()->getResultArray();

        // Riwayat Transfer
        $transfers = $this->db->query("
            SELECT st.*, 
                   p.name as product_name, p.sku,
                   l_from.name as from_location, 
                   l_to.name as to_location
            FROM stock_transfers st
            JOIN products p ON st.product_id = p.id
            JOIN locations l_from ON st.from_location_id = l_from.id
            JOIN locations l_to ON st.to_location_id = l_to.id
            ORDER BY st.created_at DESC
        ")->getResultArray();

        return view('inventory/transfer', [
            'title'        => 'Mutasi Stok - Toko Ritel Makmur',
            'page_heading' => 'Transfer Stok Antar Gudang & Gerai',
            'locations'    => $locations,
            'products'     => $products,
            'transfers'    => $transfers
        ]);
    }

    public function process()
    {
        $fromLoc   = $this->request->getPost('from_location_id');
        $toLoc     = $this->request->getPost('to_location_id');
        $productId = $this->request->getPost('product_id');
        $qty       = (int)$this->request->getPost('qty');

        if ($fromLoc == $toLoc) {
            return redirect()->back()->with('error', 'Lokasi asal dan lokasi tujuan tidak boleh sama!');
        }

        // 1. Cek ketersediaan stok di lokasi asal
        $sourceStock = $this->db->query("
            SELECT qty FROM stocks WHERE location_id = ? AND product_id = ?
        ", [$fromLoc, $productId])->getRowArray();

        if (!$sourceStock || $sourceStock['qty'] < $qty) {
            return redirect()->back()->with('error', 'Gagal Mutasi! Stok di lokasi asal tidak mencukupi.');
        }

        // Start Transaction
        $this->db->transStart();

        // 2. Kurangi stok di lokasi asal
        $this->db->query("
            UPDATE stocks SET qty = qty - ? WHERE location_id = ? AND product_id = ?
        ", [$qty, $fromLoc, $productId]);

        // 3. Tambah stok di lokasi tujuan (jika row belum ada, buat baru)
        $targetStock = $this->db->query("
            SELECT id FROM stocks WHERE location_id = ? AND product_id = ?
        ", [$toLoc, $productId])->getRowArray();

        if ($targetStock) {
            $this->db->query("
                UPDATE stocks SET qty = qty + ? WHERE location_id = ? AND product_id = ?
            ", [$qty, $toLoc, $productId]);
        } else {
            $this->db->table('stocks')->insert([
                'location_id' => $toLoc,
                'product_id'  => $productId,
                'qty'         => $qty
            ]);
        }

        // 4. Catat Log Transfer
        $transferCode = 'TRX-' . date('Ymd') . '-' . rand(100, 999);
        $this->db->table('stock_transfers')->insert([
            'transfer_code'    => $transferCode,
            'from_location_id' => $fromLoc,
            'to_location_id'   => $toLoc,
            'product_id'       => $productId,
            'qty'              => $qty,
            'created_at'       => date('Y-m-d H:i:s')
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memindahkan stok.');
        }

        return redirect()->to('inventory/transfer')->with('success', "Berhasil memindahkan $qty unit barang! ($transferCode)");
    }
}