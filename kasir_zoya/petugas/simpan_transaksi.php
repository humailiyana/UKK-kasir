<?php
session_start();
include '../config/koneksi.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $nama   = mysqli_real_escape_string($koneksi, $data['nama_pelanggan']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);
    $telp   = mysqli_real_escape_string($koneksi, $data['no_telp']);
    
    $total  = $data['total_harga'];
    $tgl    = date('Y-m-d');

    $query_pelanggan = mysqli_query($koneksi, "INSERT INTO pelanggan (NamaPelanggan, Alamat, `No.Telp`) 
                                              VALUES ('$nama', '$alamat', '$telp')");
    
    if ($query_pelanggan) {
        $id_pelanggan = mysqli_insert_id($koneksi);

        $jual = mysqli_query($koneksi, "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) 
                                        VALUES ('$tgl', '$total', '$id_pelanggan')");
        
        $id_penjualan = mysqli_insert_id($koneksi);

        if ($jual) {
            foreach ($data['items'] as $item) {
                $p_id = $item['id'];
                $qty  = $item['qty'];
                $sub  = $item['subtotal'];

                mysqli_query($koneksi, "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                                        VALUES ('$id_penjualan', '$p_id', '$qty', '$sub')");

                mysqli_query($koneksi, "UPDATE produk SET Stok = Stok - $qty WHERE ProdukID = '$p_id'");
            }
            
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal simpan penjualan']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal simpan pelanggan']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
}
?>