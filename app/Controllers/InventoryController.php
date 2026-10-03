<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class InventoryController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Ambil daftar semua lokasi
        $locations = $this->db->table('locations')->get()->getResultArray();

        // 2. Ambil daftar semua produk
        $products = $this->db->table('products')->get()->getResultArray();

        // 3. Ambil data stok dan petakan berdasarkan [product_id][location_id]
        $stocksRaw = $this->db->table('stocks')->get()->getResultArray();
        $stocks = [];
        foreach ($stocksRaw as $s) {
            $stocks[$s['product_id']][$s['location_id']] = $s['qty'];
        }

        return view('inventory/index', [
            'title'        => 'Stok Multi-Gudang - Toko Ritel Makmur',
            'page_heading' => 'Monitoring Stok Multi-Lokasi Real-Time',
            'locations'    => $locations,
            'products'     => $products,
            'stocks'       => $stocks
        ]);
    }
}