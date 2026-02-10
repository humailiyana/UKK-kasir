<?php
session_start();
include '../config/koneksi.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data) {
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $data['nama_pelanggan']);
    $alamat         = mysqli_real_escape_string($koneksi, $data['alamat']);
    $no_telp        = mysqli_real_escape_string($koneksi, $data['no_telp']);
    $total_harga    = $data['total_harga'];
    $items          = $data['items']; 
    $tanggal        = date('Y-m-d');

    $query_pelanggan = mysqli_query($koneksi, "INSERT INTO pelanggan (NamaPelanggan, Alamat, `No.Telp`) 
                                              VALUES ('$nama_pelanggan', '$alamat', '$no_telp')");

    if ($query_pelanggan) {
        $id_pelanggan = mysqli_insert_id($koneksi);

        $query_penjualan = mysqli_query($koneksi, "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) 
                                                  VALUES ('$tanggal', '$total_harga', '$id_pelanggan')");
        
        if ($query_penjualan) {
            $id_penjualan = mysqli_insert_id($koneksi);

            foreach ($items as $item) {
                $produk_id = $item['id'];
                $qty       = $item['qty'];
                $subtotal  = $item['subtotal'];

                mysqli_query($koneksi, "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                                        VALUES ('$id_penjualan', '$produk_id', '$qty', '$subtotal')");

                mysqli_query($koneksi, "UPDATE produk SET Stok = Stok - $qty WHERE ProdukID = '$produk_id'");
            }

            echo json_encode(['status' => 'success', 'id_penjualan' => $id_penjualan]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal simpan penjualan']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal simpan pelanggan']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak diterima']);
}
?>