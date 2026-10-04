<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Ringkasan Total Stok per Lokasi
        $stocksByLocation = $this->db->query("
            SELECT l.id, l.name as location_name, l.type, COALESCE(SUM(s.qty), 0) as total_items
            FROM locations l
            LEFT JOIN stocks s ON l.id = s.location_id
            GROUP BY l.id
        ")->getResultArray();

        // 2. Alert Reorder Point (Stok Kritis)
        $lowStockAlerts = $this->db->query("
            SELECT p.sku, p.name as product_name, p.min_stock, 
                   s.qty as current_qty, l.name as location_name
            FROM stocks s
            JOIN products p ON s.product_id = p.id
            JOIN locations l ON s.location_id = l.id
            WHERE s.qty <= p.min_stock
            ORDER BY s.qty ASC
        ")->getResultArray();

        // 3. Ringkasan Penjualan Hari Ini
        $todaySales = $this->db->query("
            SELECT COUNT(*) as total_tx, COALESCE(SUM(total_amount), 0) as total_revenue
            FROM sales
            WHERE DATE(created_at) = CURDATE()
        ")->getRowArray();

        // 4. Transaksi Kasir Terbaru (5 Transaksi Terakhir)
        $recentSales = $this->db->query("
            SELECT s.invoice_number, s.total_amount, s.created_at, l.name as location_name
            FROM sales s
            JOIN locations l ON s.location_id = l.id
            ORDER BY s.created_at DESC
            LIMIT 5
        ")->getResultArray();

        // 5. Top 5 Produk Terlaris (Fast-Moving Items)
        $topProducts = $this->db->query("
            SELECT p.name, p.sku, SUM(si.qty) as total_sold, p.unit
            FROM sale_items si
            JOIN products p ON si.product_id = p.id
            GROUP BY si.product_id
            ORDER BY total_sold DESC
            LIMIT 5
        ")->getResultArray();

        // 6. Tren Penjualan 7 Hari Terakhir (100% REAL DATABASE)
        $daysIndo = ['Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'];
        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $targetDate = date('Y-m-d', strtotime("-$i days"));
            $dayName    = date('D', strtotime($targetDate));

            $salesOnDate = $this->db->query("
                SELECT COALESCE(SUM(total_amount), 0) as total
                FROM sales
                WHERE DATE(created_at) = ?
            ", [$targetDate])->getRowArray();

            $chartLabels[] = $daysIndo[$dayName] . ' (' . date('d/m', strtotime($targetDate)) . ')';
            $chartData[]   = (float)($salesOnDate['total'] ?? 0);
        }

        $data = [
            'title'            => 'Dashboard Manajemen - Toko Ritel Makmur',
            'page_heading'     => 'Dashboard Analitik & Ringkasan Operasional',
            'stocksByLocation' => $stocksByLocation,
            'lowStockAlerts'   => $lowStockAlerts,
            'todaySales'       => $todaySales,
            'recentSales'      => $recentSales,
            'topProducts'      => $topProducts,
            'chartLabels'      => $chartLabels,
            'chartData'        => $chartData,
        ];

        return view('dashboard/index', $data);
    }
}