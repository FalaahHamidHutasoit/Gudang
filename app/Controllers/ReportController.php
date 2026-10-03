<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ReportController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Query Rekap Penjualan Berdasarkan Tanggal
        $sales = $this->db->query("
            SELECT s.invoice_number, s.total_amount, s.created_at, l.name as location_name
            FROM sales s
            JOIN locations l ON s.location_id = l.id
            WHERE DATE(s.created_at) BETWEEN ? AND ?
            ORDER BY s.created_at DESC
        ", [$startDate, $endDate])->getResultArray();

        // Total Akumulasi Omzet & Transaksi
        $summary = $this->db->query("
            SELECT COUNT(*) as total_tx, COALESCE(SUM(total_amount), 0) as grand_total
            FROM sales
            WHERE DATE(created_at) BETWEEN ? AND ?
        ", [$startDate, $endDate])->getRowArray();

        return view('reports/index', [
            'title'        => 'Laporan Penjualan - Toko Ritel Makmur',
            'page_heading' => 'Laporan Rekapitulasi Penjualan',
            'sales'        => $sales,
            'summary'      => $summary,
            'startDate'    => $startDate,
            'endDate'      => $endDate
        ]);
    }
}