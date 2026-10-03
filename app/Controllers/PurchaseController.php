<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PurchaseController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $orders = $this->db->query("
            SELECT po.*, l.name as warehouse_name
            FROM purchase_orders po
            JOIN locations l ON po.target_location_id = l.id
            ORDER BY po.created_at DESC
        ")->getResultArray();

        return view('purchase/index', [
            'title'        => 'Pembelian & Restock - Toko Ritel Makmur',
            'page_heading' => 'Manajemen Purchase Order (PO)',
            'orders'       => $orders
        ]);
    }

    public function create()
    {
        $warehouses = $this->db->table('locations')->where('type', 'gudang')->get()->getResultArray();
        $products   = $this->db->table('products')->get()->getResultArray();

        return view('purchase/create', [
            'title'        => 'Buat PO - Toko Ritel Makmur',
            'page_heading' => 'Terbitkan Purchase Order Baru',
            'warehouses'   => $warehouses,
            'products'     => $products
        ]);
    }

    public function store()
    {
        $supplierName = $this->request->getPost('supplier_name');
        $targetLoc    = $this->request->getPost('target_location_id');
        $productId    = $this->request->getPost('product_id');
        $qty          = (int)$this->request->getPost('qty');
        $unitCost     = (float)$this->request->getPost('unit_cost');
        $totalAmount  = $qty * $unitCost;

        $poNumber = 'PO-' . date('Ymd') . '-' . rand(100, 999);

        $this->db->transStart();

        // 1. Simpan Header PO
        $this->db->table('purchase_orders')->insert([
            'po_number'          => $poNumber,
            'supplier_name'      => $supplierName,
            'target_location_id' => $targetLoc,
            'status'             => 'pending',
            'total_amount'       => $totalAmount,
            'created_at'         => date('Y-m-d H:i:s')
        ]);
        $poId = $this->db->insertID();

        // 2. Simpan Item
        $this->db->table('po_items')->insert([
            'po_id'      => $poId,
            'product_id' => $productId,
            'qty'        => $qty,
            'unit_cost'  => $unitCost
        ]);

        $this->db->transComplete();

        return redirect()->to('purchase')->with('success', "Purchase Order $poNumber berhasil diterbitkan!");
    }

    // Fitur Konfirmasi Penerimaan Barang di Gudang (Restock Stok)
    public function receive($poId)
    {
        $po = $this->db->table('purchase_orders')->where('id', $poId)->get()->getRowArray();

        if (!$po || $po['status'] === 'received') {
            return redirect()->back()->with('error', 'PO tidak ditemukan atau sudah diterima sebelumnya.');
        }

        $items = $this->db->table('po_items')->where('po_id', $poId)->get()->getResultArray();

        $this->db->transStart();

        // 1. Update Status PO ke Received
        $this->db->table('purchase_orders')->where('id', $poId)->update(['status' => 'received']);

        // 2. Tambah Stok di Gudang Tujuan
        foreach ($items as $item) {
            $stockExist = $this->db->query("
                SELECT id FROM stocks WHERE location_id = ? AND product_id = ?
            ", [$po['target_location_id'], $item['product_id']])->getRowArray();

            if ($stockExist) {
                $this->db->query("
                    UPDATE stocks SET qty = qty + ? WHERE location_id = ? AND product_id = ?
                ", [$item['qty'], $po['target_location_id'], $item['product_id']]);
            } else {
                $this->db->table('stocks')->insert([
                    'location_id' => $po['target_location_id'],
                    'product_id'  => $item['product_id'],
                    'qty'         => $item['qty']
                ]);
            }
        }

        $this->db->transComplete();

        return redirect()->to('purchase')->with('success', "Barang dari {$po['po_number']} telah diterima dan stok gudang otomatis bertambah!");
    }
}