<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Home extends BaseController
{
    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        // Cek hak akses
        $role = session()->get('role');

        if (!in_array($role, ['owner', 'karyawan'])) {
            session()->destroy();
            return redirect()->to('/');
        }

        $barangModel = new BarangModel();
        $db = \Config\Database::connect();

        // =====================================
        // RINGKASAN DASHBOARD
        // =====================================

        // Total jenis barang
        $data['total_barang'] = $barangModel->countAll();

        // Total seluruh stok
        $hasilStok = $barangModel
            ->selectSum('stok', 'total')
            ->first();

        $data['total_stok'] = $hasilStok['total'] ?? 0;

        // Total barang hampir habis
        $data['barang_hampir_habis'] = $barangModel
            ->where('stok <=', 5)
            ->countAllResults();

        // Total transaksi hari ini
        $data['transaksi_hari_ini'] = $db->table('transaksi')
            ->where('DATE(tanggal_transaksi)', date('Y-m-d'))
            ->countAllResults();

        // =====================================
        // AKTIVITAS TERBARU
        // =====================================

        $data['aktivitas_terbaru'] = $db->table('transaksi t')
            ->select('
                t.nomor_transaksi,
                t.jenis_transaksi,
                t.tanggal_transaksi,
                u.nama AS nama_akun,
                b.nama_barang,
                dt.jumlah
            ')
            ->join('users u', 'u.id = t.user_id', 'left')
            ->join('detail_transaksi dt', 'dt.transaksi_id = t.id', 'left')
            ->join('barang b', 'b.id = dt.barang_id', 'left')
            ->orderBy('t.tanggal_transaksi', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // =====================================
        // GRAFIK TRANSAKSI 7 HARI TERAKHIR
        // =====================================

        $tanggalMulai = date('Y-m-d', strtotime('-6 days'));
        $tanggalAkhir = date('Y-m-d');

        $hasilGrafik = $db->table('transaksi')
            ->select("
                DATE(tanggal_transaksi) AS tanggal,
                jenis_transaksi,
                COUNT(*) AS total
            ", false)
            ->where('tanggal_transaksi >=', $tanggalMulai . ' 00:00:00')
            ->where('tanggal_transaksi <=', $tanggalAkhir . ' 23:59:59')
            ->groupBy('DATE(tanggal_transaksi)', false)
            ->groupBy('jenis_transaksi')
            ->get()
            ->getResultArray();

        // Siapkan data grafik
        $rekapGrafik = [];

        foreach ($hasilGrafik as $hasil) {
            $rekapGrafik[$hasil['tanggal']][$hasil['jenis_transaksi']]
                = (int) $hasil['total'];
        }

        $data['grafik_labels'] = [];
        $data['grafik_masuk'] = [];
        $data['grafik_keluar'] = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));

            $data['grafik_labels'][] = date('d/m', strtotime($tanggal));

            $data['grafik_masuk'][] =
                $rekapGrafik[$tanggal]['masuk'] ?? 0;

            $data['grafik_keluar'][] =
                $rekapGrafik[$tanggal]['keluar'] ?? 0;
        }

        // =====================================
        // DAFTAR BARANG HAMPIR HABIS
        // =====================================

        $data['barang_stok_menipis'] = $barangModel
            ->select('id, kode_barang, nama_barang, stok')
            ->where('stok <=', 5)
            ->orderBy('stok', 'ASC')
            ->orderBy('nama_barang', 'ASC')
            ->findAll();

        $data['total_barang_stok_menipis'] =
            count($data['barang_stok_menipis']);

        // =====================================
        // TAMPILKAN DASHBOARD
        // =====================================

        return view('dashboard', $data);
    }
}
