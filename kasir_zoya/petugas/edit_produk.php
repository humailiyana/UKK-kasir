<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "petugas") {
    header("location:../auth/login.php");
    exit;
}

include '../templates/header.php';
include '../templates/sidebar_petugas.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE ProdukID='$id'");
$d = mysqli_fetch_array($query);
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-dark text-white py-3" style="border-radius: 15px 15px 0 0;">
                    <h5 class="m-0"><i class="fas fa-edit me-2" style="color: #ff6b9d;"></i> Update Stok Bunga</h5>
                </div>
                <div class="card-body p-4">
                    <form action="proses_update_stok.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $d['ProdukID']; ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bunga</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $d['NamaProduk']; ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #ff6b9d;">Jumlah Stok Baru</label>
                            <input type="number" name="stok_baru" class="form-control" placeholder="Masukkan total stok..." required autofocus>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="stok.php" class="btn btn-light px-4" style="border-radius: 20px;">Kembali</a>
                            <button type="submit" class="btn text-white px-4" style="background-color: #ff6b9d; border-radius: 20px;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../templates/footer.php'; ?>