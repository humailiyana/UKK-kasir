<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "admin") {
    header("location:../auth/login.php");
    exit;
}

if (isset($_POST['registrasi'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['Username']);
    $password = md5($_POST['Password']); 
    $role = $_POST['Role'];

    $cek_user = mysqli_query($koneksi, "SELECT * FROM user WHERE Username='$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        header("location:registrasi.php?pesan=gagal");
    } else {
        $query = mysqli_query($koneksi, "INSERT INTO user (Username, Password, Role) VALUES ('$username', '$password', '$role')");
        if ($query) {
            header("location:registrasi.php?pesan=simpan");
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $session_id = $_SESSION['UserID'] ?? 0;

    if ($id == $session_id) {
        header("location:registrasi.php?pesan=self_delete");
    } else {
        $query_hapus = mysqli_query($koneksi, "DELETE FROM user WHERE UserID='$id'");
        if ($query_hapus) {
            header("location:registrasi.php?pesan=hapus");
        }
    }
}

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0"><i class="fas fa-user-plus text-pink me-2"></i> Registrasi <span class="text-pink">Pengguna</span></h2>
            <p class="text-muted">Kelola akun Admin dan Petugas.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button type="button" class="btn btn-pink shadow-sm px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="fas fa-plus-circle me-2"></i> Buat Akun Baru
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h5 class="fw-bold"><i class="fas fa-users text-pink me-2"></i> Akun Terdaftar</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3">No</th>
                            <th>Username</th>
                            <th>Role / Hak Akses</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM user ORDER BY Role ASC, Username ASC");
                        while($d = mysqli_fetch_array($query)){
                            $my_id = $_SESSION['UserID'] ?? 0;
                        ?>
                        <tr>
                            <td class="ps-4"><?php echo $no++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-3 text-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                    <span class="fw-bold"><?php echo $d['Username']; ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if($d['Role'] == 'admin'): ?>
                                    <span class="badge bg-dark px-3 rounded-pill"><i class="fas fa-shield-alt me-1 text-pink"></i> Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark border px-3 rounded-pill">Petugas</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <?php if($d['UserID'] != $my_id): ?>
                                <button onclick="konfirmasiHapus(<?php echo $d['UserID']; ?>, '<?php echo $d['Username']; ?>')" class="btn btn-sm btn-pink rounded-pill px-3">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                </button>
                                <?php else: ?>
                                <span class="badge bg-success small px-3 rounded-pill">Akun Anda</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form action="" method="POST">
                <div class="modal-header border-0 bg-dark text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title fw-bold">Registrasi Petugas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="Username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="Password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select name="Role" class="form-select" required>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" name="registrasi" class="btn btn-pink w-100">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function konfirmasiHapus(id, nama) {
    Swal.fire({
        title: 'Hapus Akun?',
        text: "Akun '" + nama + "' akan dihapus.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff69b4',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "registrasi.php?hapus=" + id;
        }
    })
}

<?php if(isset($_GET['pesan'])): ?>
    Swal.fire({
        icon: '<?php echo ($_GET['pesan'] == 'simpan' ? 'success' : ($_GET['pesan'] == 'hapus' ? 'error' : 'warning')); ?>',
        title: 'Informasi',
        text: '<?php 
            if($_GET['pesan'] == 'simpan') echo "Akun berhasil dibuat.";
            if($_GET['pesan'] == 'hapus') echo "Akun telah dihapus.";
            if($_GET['pesan'] == 'gagal') echo "Username sudah ada.";
            if($_GET['pesan'] == 'self_delete') echo "Tidak bisa menghapus akun sendiri.";
        ?>',
        confirmButtonColor: '#ff69b4'
    });
<?php endif; ?>
</script>

<?php include '../templates/footer.php'; ?>