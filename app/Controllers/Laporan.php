<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $role = session()->get('role');

        if (!in_array($role, ['owner', 'karyawan'])) {
            session()->destroy();
            return redirect()->to('/');
        }

        $isOwner = ($role === 'owner');
        $db = \Config\Database::connect();

        $periode = $this->request->getGet('periode') ?? 'harian';
        $hariIni = new \DateTimeImmutable('today');

        switch ($periode) {
            case 'mingguan':
                $awal = $hariIni->modify('monday this week');
                $akhir = $hariIni->modify('sunday this week');
                break;

            case 'bulanan':
                $awal = $hariIni->modify('first day of this month');
                $akhir = $hariIni->modify('last day of this month');
                break;

            case 'tahunan':
                $awal = $hariIni->setDate((int) $hariIni->format('Y'), 1, 1);
                $akhir = $hariIni->setDate((int) $hariIni->format('Y'), 12, 31);
                break;

            case 'custom':
                $tanggalAwal = (string) ($this->request->getGet('tanggal_awal') ?? '');
                $tanggalAkhir = (string) ($this->request->getGet('tanggal_akhir') ?? '');

                if ($tanggalAwal === '' || $tanggalAkhir === '') {
                    $awal = $hariIni->modify('first day of this month');
                    $akhir = $hariIni;
                } else {
                    $awal = \DateTime::createFromFormat('!Y-m-d', $tanggalAwal);
                    $akhir = \DateTime::createFromFormat('!Y-m-d', $tanggalAkhir);

                    if (
                        !$awal || !$akhir ||
                        $awal->format('Y-m-d') !== $tanggalAwal ||
                        $akhir->format('Y-m-d') !== $tanggalAkhir ||
                        $tanggalAwal > $tanggalAkhir
                    ) {
                        $awal = $hariIni->modify('first day of this month');
                        $akhir = $hariIni;
                    }
                }
                break;

            default:
                $periode = 'harian';
                $awal = $hariIni;
                $akhir = $hariIni;
                break;
        }

        $tanggalAwal = $awal->format('Y-m-d');
        $tanggalAkhir = $akhir->format('Y-m-d');
        $tanggalBatas = $akhir->modify('+1 day')->format('Y-m-d');

        $data['role'] = $role;
        $data['periode'] = $periode;
        $data['tanggal_awal'] = $tanggalAwal;
        $data['tanggal_akhir'] = $tanggalAkhir;

        $data['total_barang'] = $db->table('barang')->countAll();

        $stok = $db->table('barang')
            ->selectSum('stok')
            ->get()
            ->getRowArray();

        $data['total_stok'] = $stok['stok'] ?? 0;

        if ($isOwner) {
            $data['daftar_barang'] = $db->table('barang')
                ->orderBy('nama_barang', 'ASC')
                ->get()
                ->getResultArray();
        } else {
            $data['daftar_barang'] = $db->table('barang')
                ->select('id, kode_barang, nama_barang, stok, tanggal_masuk')
                ->orderBy('nama_barang', 'ASC')
                ->get()
                ->getResultArray();
        }

        if ($isOwner) {
            $masuk = $db->table('transaksi')
                ->selectSum('total_harga')
                ->where('jenis_transaksi', 'masuk')
                ->where('tanggal_transaksi >=', $tanggalAwal)
                ->where('tanggal_transaksi <', $tanggalBatas)
                ->get()
                ->getRowArray();

            $keluar = $db->table('transaksi')
                ->selectSum('total_harga')
                ->where('jenis_transaksi', 'keluar')
                ->where('tanggal_transaksi >=', $tanggalAwal)
                ->where('tanggal_transaksi <', $tanggalBatas)
                ->get()
                ->getRowArray();

            $data['total_masuk'] = $masuk['total_harga'] ?? 0;
            $data['total_keluar'] = $keluar['total_harga'] ?? 0;
        }

        $kolomTransaksi = '
            t.nomor_transaksi,
            t.jenis_transaksi,
            t.tanggal_transaksi,
            u.nama AS nama_akun,
            b.kode_barang,
            b.nama_barang,
            dt.jumlah
        ';

        if ($isOwner) {
            $kolomTransaksi .= ', dt.subtotal';
        }

        $data['riwayat_transaksi'] = $db->table('transaksi t')
            ->select($kolomTransaksi)
            ->join('detail_transaksi dt', 'dt.transaksi_id = t.id')
            ->join('barang b', 'b.id = dt.barang_id')
            ->join('users u', 'u.id = t.user_id', 'left')
            ->where('t.tanggal_transaksi >=', $tanggalAwal)
            ->where('t.tanggal_transaksi <', $tanggalBatas)
            ->orderBy('t.tanggal_transaksi', 'DESC')
            ->get()
            ->getResultArray();

        return view('laporan/index', $data);
    }
}
