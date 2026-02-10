<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "admin") {
    header("location:../auth/login.php");
    exit;
}

if (isset($_POST['tambah'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['NamaProduk']);
    $harga = $_POST['Harga'];
    $stok = $_POST['Stok'];

    $query = mysqli_query($koneksi, "INSERT INTO produk (NamaProduk, Harga, Stok) VALUES ('$nama_produk', '$harga', '$stok')");
    if ($query) {
        header("location:produk.php?pesan=simpan");
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query_hapus = mysqli_query($koneksi, "DELETE FROM produk WHERE ProdukID='$id'");
    if ($query_hapus) {
        header("location:produk.php?pesan=hapus");
    }
}

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0"><i class="fas fa-boxes text-pink me-2"></i> Pendataan <span class="text-pink">Barang</span></h2>
            <p class="text-muted">Kelola stok produk toko bunga Zoya.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button type="button" class="btn btn-pink shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#tambahBarang">
                <i class="fas fa-plus-circle me-2"></i> Tambah Produk Bunga
            </button>
        </div>
    </div>

    <?php if(isset($_GET['pesan'])): ?>
        <div class="alert alert-<?php echo ($_GET['pesan'] == 'hapus' ? 'danger' : 'success'); ?> border-0 shadow-sm alert-dismissible fade show">
            <?php 
                if($_GET['pesan'] == "simpan") echo "Produk berhasil ditambahkan!";
                if($_GET['pesan'] == "update") echo "Data produk berhasil diperbarui!";
                if($_GET['pesan'] == "hapus") echo "Produk telah berhasil dihapus.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Bunga</th>
                            <th>Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $data = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY ProdukID DESC");
                        while($d = mysqli_fetch_array($data)){
                        ?>
                        <tr>
                            <td class="ps-4"><?php echo $no++; ?></td>
                            <td class="fw-bold"><?php echo $d['NamaProduk']; ?></td>
                            <td>Rp <?php echo number_format($d['Harga'], 0, ',', '.'); ?></td>
                            <td class="text-center"><?php echo $d['Stok']; ?></td>
                            <td class="text-center pe-4">
                                <button class="btn btn-sm btn-outline-dark me-1" data-bs-toggle="modal" data-bs-target="#editBarang<?php echo $d['ProdukID']; ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-pink" onclick="hapusProduk(<?php echo $d['ProdukID']; ?>, '<?php echo $d['NamaProduk']; ?>')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editBarang<?php echo $d['ProdukID']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <form action="proses_edit_produk.php" method="POST">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title">Edit Produk</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="ProdukID" value="<?php echo $d['ProdukID']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Bunga</label>
                                                <input type="text" name="NamaProduk" class="form-control" value="<?php echo $d['NamaProduk']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Harga</label>
                                                <input type="number" name="Harga" class="form-control" value="<?php echo $d['Harga']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Stok</label>
                                                <input type="number" name="Stok" class="form-control" value="<?php echo $d['Stok']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update" class="btn btn-pink">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="" method="POST">
                <div class="modal-header bg-pink text-white">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Bunga</label>
                        <input type="text" name="NamaProduk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="Harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="Stok" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-pink w-100">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function hapusProduk(id, nama) {
    Swal.fire({
        title: 'Hapus Produk?',
        text: "Anda akan menghapus " + nama + ". Tindakan ini tidak bisa dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff69b4', 
        cancelButtonColor: '#212529',  
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-4 shadow-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "produk.php?hapus=" + id;
        }
    })
}
</script>

<?php include '../templates/footer.php'; ?>