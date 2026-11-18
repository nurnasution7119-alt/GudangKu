<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;

class Transaksi extends BaseController
{
    protected $barangModel;
    protected $transaksiModel;
    protected $detailTransaksiModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
    }

    // Memeriksa login dan hak akses
    private function cekLogin()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        if (!in_array(session()->get('role'), ['owner', 'karyawan'])) {
            session()->destroy();
            return redirect()->to('/');
        }

        return null;
    }

    // Khusus fitur Owner
    private function cekOwner()
    {
        $akses = $this->cekLogin();

        if ($akses !== null) {
            return $akses;
        }

        if (session()->get('role') !== 'owner') {
            return redirect()->to(base_url('transaksi'))
                ->with('error', 'Akses ditolak! Fitur ini hanya untuk Owner.');
        }

        return null;
    }

    // Menampilkan halaman transaksi
    public function index()
    {
        $akses = $this->cekLogin();

        if ($akses !== null) {
            return $akses;
        }

        $role = session()->get('role');

        if ($role === 'owner') {
            $data['barang'] = $this->barangModel->findAll();
        } else {
            $data['barang'] = $this->barangModel
                ->select('id, kode_barang, nama_barang, stok, harga_jual')
                ->findAll();
        }

        $data['role'] = $role;

        return view('transaksi/index', $data);
    }

    // Menampilkan halaman barang masuk (Owner saja)
    public function barangMasuk()
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $data['barang'] = $this->barangModel->findAll();
        $data['role'] = 'owner';

        return view('transaksi/barang_masuk', $data);
    }

    // Menyimpan transaksi barang masuk (Owner saja)
    public function simpanMasuk()
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $barangId = (int) $this->request->getPost('barang_id');
        $jumlah = (int) $this->request->getPost('jumlah');
        $hargaModal = (float) $this->request->getPost('harga_modal');

        if ($barangId <= 0 || $jumlah <= 0 || $hargaModal < 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data transaksi tidak valid.');
        }

        $barang = $this->barangModel->find($barangId);

        if (!$barang) {
            return redirect()->back()
                ->with('error', 'Barang tidak ditemukan.');
        }

        $totalHarga = $jumlah * $hargaModal;

        $nomorTransaksi = 'TRX-M-' . date('YmdHis') . random_int(100, 999);

        $db = \Config\Database::connect();
        $db->transBegin();

        $berhasil = $this->transaksiModel->insert([
            'nomor_transaksi' => $nomorTransaksi,
            'user_id' => session()->get('user_id'),
            'jenis_transaksi' => 'masuk',
            'total_harga' => $totalHarga
        ]);

        $transaksiId = $this->transaksiModel->getInsertID();

        if (!$berhasil || !$transaksiId) {
            $db->transRollback();

            return redirect()->back()
                ->with('error', 'Transaksi gagal disimpan.');
        }

        $detailBerhasil = $this->detailTransaksiModel->insert([
            'transaksi_id' => $transaksiId,
            'barang_id' => $barangId,
            'jumlah' => $jumlah,
            'harga_modal' => $hargaModal,
            'harga_jual' => $barang['harga_jual'],
            'subtotal' => $totalHarga
        ]);

        if (!$detailBerhasil) {
            $db->transRollback();

            return redirect()->back()
                ->with('error', 'Detail transaksi gagal disimpan.');
        }

        $stokBaru = (int) $barang['stok'] + $jumlah;

        $barangBerhasil = $this->barangModel->update($barangId, [
            'stok' => $stokBaru,
            'harga_modal' => $hargaModal
        ]);

        if (!$barangBerhasil || $db->transStatus() === false) {
            $db->transRollback();

            return redirect()->back()
                ->with('error', 'Stok barang gagal diperbarui.');
        }

        $db->transCommit();

        return redirect()->to(base_url('transaksi'))
            ->with('success', 'Barang masuk berhasil disimpan dan stok berhasil ditambahkan.');
    }

    // Menampilkan halaman barang keluar
    public function barangKeluar()
    {
        $akses = $this->cekLogin();

        if ($akses !== null) {
            return $akses;
        }

        $role = session()->get('role');

        if ($role === 'owner') {
            $data['barang'] = $this->barangModel->findAll();
        } else {
            // Karyawan tidak mengambil kolom harga
            $data['barang'] = $this->barangModel
                ->select('id, kode_barang, nama_barang, stok')
                ->findAll();
        }

        $data['role'] = $role;

        return view('transaksi/barang_keluar', $data);
    }

    // Menyimpan transaksi barang keluar
    public function simpanKeluar()
    {
        $akses = $this->cekLogin();

        if ($akses !== null) {
            return $akses;
        }

        $barangId = (int) $this->request->getPost('barang_id');
        $jumlah = (int) $this->request->getPost('jumlah');

        if ($barangId <= 0 || $jumlah <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data transaksi tidak valid.');
        }

        $barang = $this->barangModel->find($barangId);

        if (!$barang) {
            return redirect()->back()
                ->with('error', 'Barang tidak ditemukan.');
        }

        if ($jumlah > (int) $barang['stok']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Stok barang tidak mencukupi.');
        }

        // Harga untuk pencatatan internal
        $hargaModal = (float) $barang['harga_modal'];
        $hargaJual = (float) $barang['harga_jual'];
        $totalHarga = $jumlah * $hargaJual;

        $nomorTransaksi = 'TRX-K-' . date('YmdHis') . random_int(100, 999);

        $db = \Config\Database::connect();
        $db->transBegin();

        $berhasil = $this->transaksiModel->insert([
            'nomor_transaksi' => $nomorTransaksi,
            'user_id' => session()->get('user_id'),
            'jenis_transaksi' => 'keluar',
            'total_harga' => $totalHarga
        ]);

        $transaksiId = $this->transaksiModel->getInsertID();

        if (!$berhasil || !$transaksiId) {
            $db->transRollback();

            return redirect()->back()
                ->with('error', 'Transaksi gagal disimpan.');
        }

        $detailBerhasil = $this->detailTransaksiModel->insert([
            'transaksi_id' => $transaksiId,
            'barang_id' => $barangId,
            'jumlah' => $jumlah,
            'harga_modal' => $hargaModal,
            'harga_jual' => $hargaJual,
            'subtotal' => $totalHarga
        ]);

        if (!$detailBerhasil) {
            $db->transRollback();

            return redirect()->back()
                ->with('error', 'Detail transaksi gagal disimpan.');
        }

        $stokBaru = (int) $barang['stok'] - $jumlah;

        $barangBerhasil = $this->barangModel->update($barangId, [
            'stok' => $stokBaru
        ]);

        if (!$barangBerhasil || $db->transStatus() === false) {
            $db->transRollback();

            return redirect()->back()
                ->with('error', 'Stok barang gagal diperbarui.');
        }

        $db->transCommit();

        return redirect()->to(base_url('transaksi'))
            ->with('success', 'Barang keluar berhasil disimpan dan stok berhasil dikurangi.');
    }

    // Menampilkan riwayat transaksi
    public function riwayat()
    {
        $akses = $this->cekLogin();

        if ($akses !== null) {
            return $akses;
        }

        $role = session()->get('role');
        $db = \Config\Database::connect();

        $kolom = '
            t.id,
            t.nomor_transaksi,
            t.jenis_transaksi,
            t.tanggal_transaksi,
            b.kode_barang,
            b.nama_barang,
            d.jumlah
        ';

        // Total harga hanya dikirim kepada Owner
        if ($role === 'owner') {
            $kolom .= ', t.total_harga';
        }

        $data['riwayat'] = $db->table('transaksi t')
            ->select($kolom)
            ->join('detail_transaksi d', 'd.transaksi_id = t.id')
            ->join('barang b', 'b.id = d.barang_id')
            ->orderBy('t.id', 'DESC')
            ->get()
            ->getResultArray();

        $data['role'] = $role;

        return view('transaksi/riwayat', $data);
    }

    // Menampilkan nota transaksi (Owner saja)
    public function nota($id)
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $db = \Config\Database::connect();

        $nota = $db->table('transaksi t')
            ->select('
                t.id,
                t.nomor_transaksi,
                t.tanggal_transaksi,
                t.jenis_transaksi,
                t.total_harga,
                b.kode_barang,
                b.nama_barang,
                d.jumlah,
                d.harga_jual,
                d.subtotal
            ')
            ->join('detail_transaksi d', 'd.transaksi_id = t.id')
            ->join('barang b', 'b.id = d.barang_id')
            ->where('t.id', $id)
            ->get()
            ->getResultArray();

        if (empty($nota)) {
            return redirect()->to(base_url('transaksi/riwayat'))
                ->with('error', 'Data transaksi tidak ditemukan.');
        }

        // Nota hanya untuk transaksi barang keluar
        if ($nota[0]['jenis_transaksi'] !== 'keluar') {
            return redirect()->to(base_url('transaksi/riwayat'))
                ->with('error', 'Nota hanya tersedia untuk barang keluar.');
        }

        $data['nota'] = $nota;
        $data['role'] = session()->get('role');

        return view('transaksi/nota', $data);
    }
}
