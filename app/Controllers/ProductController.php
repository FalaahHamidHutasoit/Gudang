<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ProductController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // List Produk
    public function index()
    {
        $products = $this->db->table('products')
                             ->orderBy('id', 'DESC')
                             ->get()
                             ->getResultArray();

        return view('products/index', [
            'title'        => 'Master Produk - Toko Ritel Makmur',
            'page_heading' => 'Manajemen Master Produk',
            'products'     => $products
        ]);
    }

    // Form Tambah Produk
    public function create()
    {
        return view('products/create', [
            'title'        => 'Tambah Produk - Toko Ritel Makmur',
            'page_heading' => 'Tambah Produk Baru'
        ]);
    }

    // Simpan Produk Baru
    public function store()
    {
        $sku      = strtoupper($this->request->getPost('sku'));
        $name     = $this->request->getPost('name');
        $unit     = $this->request->getPost('unit');
        $minStock = (int)$this->request->getPost('min_stock');
        $price    = (float)$this->request->getPost('price');

        // Cek duplikasi SKU
        $exist = $this->db->table('products')->where('sku', $sku)->get()->getRowArray();
        if ($exist) {
            return redirect()->back()->with('error', "SKU '$sku' sudah digunakan oleh produk lain!");
        }

        $this->db->transStart();

        // 1. Insert ke tabel products
        $this->db->table('products')->insert([
            'sku'        => $sku,
            'name'       => $name,
            'unit'       => $unit,
            'min_stock'  => $minStock,
            'price'      => $price,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $productId = $this->db->insertID();

        // 2. Inisialisasi stok 0 di semua lokasi (Gudang 1, Gudang 2, Gerai)
        $locations = $this->db->table('locations')->get()->getResultArray();
        foreach ($locations as $loc) {
            $this->db->table('stocks')->insert([
                'location_id' => $loc['id'],
                'product_id'  => $productId,
                'qty'         => 0
            ]);
        }

        $this->db->transComplete();

        return redirect()->to('products')->with('success', "Produk '$name' berhasil ditambahkan!");
    }

    // Form Edit Produk
    public function edit($id)
    {
        $product = $this->db->table('products')->where('id', $id)->get()->getRowArray();
        if (!$product) {
            return redirect()->to('products')->with('error', 'Produk tidak ditemukan.');
        }

        return view('products/edit', [
            'title'        => 'Edit Produk - Toko Ritel Makmur',
            'page_heading' => 'Edit Data Produk',
            'product'      => $product
        ]);
    }

    // Update Produk
    public function update($id)
    {
        $name     = $this->request->getPost('name');
        $unit     = $this->request->getPost('unit');
        $minStock = (int)$this->request->getPost('min_stock');
        $price    = (float)$this->request->getPost('price');

        $this->db->table('products')->where('id', $id)->update([
            'name'      => $name,
            'unit'      => $unit,
            'min_stock' => $minStock,
            'price'     => $price,
        ]);

        return redirect()->to('products')->with('success', "Data produk berhasil diperbarui!");
    }

    // Hapus Produk
    public function delete($id)
    {
        $product = $this->db->table('products')->where('id', $id)->get()->getRowArray();
        if ($product) {
            $this->db->table('products')->where('id', $id)->delete();
            return redirect()->to('products')->with('success', "Produk '{$product['name']}' berhasil dihapus.");
        }
        return redirect()->to('products')->with('error', 'Gagal menghapus produk.');
    }
}