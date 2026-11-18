
<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Auth::chooseLogin');
$routes->get('pilih-login', 'Auth::chooseLogin');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');

$routes->get('owner-setup', 'OwnerSetup::index');
$routes->post('owner-setup', 'OwnerSetup::create');

$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');

$routes->get('verify-otp', 'Auth::verifyOtp');
$routes->post('verify-otp', 'Auth::attemptVerifyOtp');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::sendResetOtp');

$routes->get('reset-password', 'Auth::resetPassword');
$routes->post('reset-password', 'Auth::attemptResetPassword');

$routes->get('manajemen-akun', 'ManajemenAkun::index');
$routes->get('manajemen-akun/tambah', 'ManajemenAkun::tambah');
$routes->post('manajemen-akun/simpan', 'ManajemenAkun::simpan');
$routes->get('manajemen-akun/edit/(:num)', 'ManajemenAkun::edit/$1');
$routes->post('manajemen-akun/update/(:num)', 'ManajemenAkun::update/$1');
$routes->post('manajemen-akun/hapus/(:num)', 'ManajemenAkun::hapus/$1');

$routes->get('dashboard', 'Home::index');

$routes->get('logout', 'Auth::logout');

$routes->get('data-barang', 'Barang::index');
$routes->get('data-barang/tambah', 'Barang::tambah');
$routes->post('data-barang/simpan', 'Barang::simpan');
$routes->get('data-barang/edit/(:num)', 'Barang::edit/$1');
$routes->post('data-barang/update/(:num)', 'Barang::update/$1');
$routes->post('data-barang/hapus/(:num)', 'Barang::hapus/$1');

$routes->get('transaksi', 'Transaksi::index');
$routes->get('transaksi/barang-masuk', 'Transaksi::barangMasuk');
$routes->post('transaksi/simpan-masuk', 'Transaksi::simpanMasuk');
$routes->get('transaksi/barang-keluar', 'Transaksi::barangKeluar');
$routes->post('transaksi/simpan-keluar', 'Transaksi::simpanKeluar');
$routes->get('transaksi/riwayat', 'Transaksi::riwayat');
$routes->get('transaksi/nota/(:num)', 'Transaksi::nota/$1');

$routes->get('laporan', 'Laporan::index');
$routes->get('pengaturan', 'Pengaturan::index');
