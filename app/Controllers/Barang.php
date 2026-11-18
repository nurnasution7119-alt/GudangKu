<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    // Memeriksa login
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

    // Khusus Owner
    private function cekOwner()
    {
        $akses = $this->cekLogin();

        if ($akses !== null) {
            return $akses;
        }

        if (session()->get('role') !== 'owner') {
            return redirect()->to('/data-barang')
                ->with('error', 'Akses ditolak! Fitur ini hanya untuk Owner.');
        }

        return null;
    }

    // Menampilkan daftar barang
    public function index()
    {
        $akses = $this->cekLogin();

        if ($akses !== null) {
            return $akses;
        }

        $role = session()->get('role');

        $keyword = trim((string) ($this->request->getGet('keyword') ?? ''));
        $filterStok = $this->request->getGet('stok');

        $db = \Config\Database::connect();

        $builder = $db->table('barang');

        // Owner mengambil semua kolom.
        // Karyawan tidak mengambil kolom harga.
        if ($role === 'owner') {
            $builder->select('*');
        } else {
            $builder->select(
                'id, kode_barang, nama_barang, stok, tanggal_masuk, tanggal_kedaluwarsa'
            );
        }

        // Pencarian barang
        if ($keyword !== '') {
            $builder->groupStart()
                ->like('kode_barang', $keyword)
                ->orLike('nama_barang', $keyword)
                ->groupEnd();
        }

        // Filter stok
        if ($filterStok === 'menipis') {
            $builder->where('stok >', 0)
                ->where('stok <=', 5);
        } elseif ($filterStok === 'tersedia') {
            $builder->where('stok >', 5);
        } elseif ($filterStok === 'habis') {
            $builder->where('stok', 0);
        }

        $data['barang'] = $builder
            ->orderBy('nama_barang', 'ASC')
            ->get()
            ->getResultArray();

        $data['keyword'] = $keyword;
        $data['filterStok'] = $filterStok;
        $data['role'] = $role;

        return view('barang/index', $data);
    }

    // Form tambah barang
    public function tambah()
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        return view('barang/tambah');
    }

    // Simpan barang baru
    public function simpan()
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $rules = [
            'nama_barang' => 'required|max_length[100]',
            'stok' => 'required|integer|greater_than_equal_to[0]',
            'harga_modal' => 'required|numeric|greater_than_equal_to[0]',
            'harga_jual' => 'required|numeric|greater_than_equal_to[0]',
            'tanggal_masuk' => 'required|valid_date[Y-m-d]',
            'tanggal_kedaluwarsa' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->barangModel->insert([
            'kode_barang' => 'TEMP-' . uniqid(),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'stok' => $this->request->getPost('stok'),
            'harga_modal' => $this->request->getPost('harga_modal'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'tanggal_kedaluwarsa' => $this->request->getPost('tanggal_kedaluwarsa') ?: null,
            'input_oleh' => session()->get('user_id'),
        ]);

        $id = $this->barangModel->getInsertID();

        if (!$id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Barang gagal ditambahkan.');
        }

        $kodeBarang = 'BRG' . str_pad($id, 3, '0', STR_PAD_LEFT);

        $this->barangModel->update($id, [
            'kode_barang' => $kodeBarang
        ]);

        return redirect()->to('/data-barang')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    // Form edit barang
    public function edit($id)
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $barang = $this->barangModel->find($id);

        if (!$barang) {
            return redirect()->to('/data-barang')
                ->with('error', 'Barang tidak ditemukan.');
        }

        return view('barang/edit', [
            'barang' => $barang
        ]);
    }

    // Update barang
    public function update($id)
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $barang = $this->barangModel->find($id);

        if (!$barang) {
            return redirect()->to('/data-barang')
                ->with('error', 'Barang tidak ditemukan.');
        }

        $rules = [
            'nama_barang' => 'required|max_length[100]',
            'stok' => 'required|integer|greater_than_equal_to[0]',
            'harga_modal' => 'required|numeric|greater_than_equal_to[0]',
            'harga_jual' => 'required|numeric|greater_than_equal_to[0]',
            'tanggal_masuk' => 'required|valid_date[Y-m-d]',
            'tanggal_kedaluwarsa' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->barangModel->update($id, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'stok' => $this->request->getPost('stok'),
            'harga_modal' => $this->request->getPost('harga_modal'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'tanggal_kedaluwarsa' => $this->request->getPost('tanggal_kedaluwarsa') ?: null,
        ]);

        return redirect()->to('/data-barang')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    // Hapus barang
    public function hapus($id)
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $barang = $this->barangModel->find($id);

        if (!$barang) {
            return redirect()->to('/data-barang')
                ->with('error', 'Barang tidak ditemukan.');
        }

        $this->barangModel->delete($id);

        return redirect()->to('/data-barang')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
