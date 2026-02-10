<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "petugas") {
    header("location:../auth/login.php");
    exit;
}

include '../templates/header.php';
include '../templates/sidebar_petugas.php';
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-dark m-0"><i class="fas fa-cash-register text-pink me-2"></i> Transaksi <span class="text-pink">Kasir</span></h2>
            <p class="text-muted">Pilih produk, konfirmasi pembayaran, dan cetak resi otomatis.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-dark text-white p-3" style="border-radius: 15px 15px 0 0;">
                    <h5 class="m-0"><i class="fas fa-th-list me-2 text-pink"></i> Daftar Stok Tersedia</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th>Harga</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE Stok > 0 ORDER BY NamaProduk ASC");
                                while($p = mysqli_fetch_array($produk)) {
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?php echo $p['NamaProduk']; ?></td>
                                    <td>Rp <?php echo number_format($p['Harga'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <span class="badge <?php echo ($p['Stok'] < 10) ? 'bg-danger' : 'bg-dark'; ?> rounded-pill">
                                            <?php echo $p['Stok']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-pink btn-sm rounded-pill px-3 fw-bold" 
                                                onclick="tambahItem(<?php echo $p['ProdukID']; ?>, '<?php echo $p['NamaProduk']; ?>', <?php echo $p['Harga']; ?>, <?php echo $p['Stok']; ?>)">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-pink text-white p-3" style="border-radius: 15px 15px 0 0;">
                    <h5 class="m-0 fw-bold"><i class="fas fa-shopping-cart me-2"></i> Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="small fw-bold">Nama Pelanggan</label>
                        <input type="text" id="namaPelanggan" class="form-control border-dark" placeholder="Nama Lengkap...">
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">Alamat</label>
                        <textarea id="alamatPelanggan" class="form-control border-dark" rows="2" placeholder="Alamat Lengkap..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">No. Telp</label>
                        <input type="text" id="telpPelanggan" class="form-control border-dark" placeholder="08xxxxxxxxxx">
                    </div>
                    
                    <div class="table-responsive mb-3" style="min-height: 150px; max-height: 250px;">
                        <table class="table table-sm">
                            <tbody id="listPesanan">
                                </tbody>
                        </table>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold">Total Harga</span>
                            <span class="fw-bold text-pink h4 m-0" id="textTotal">Rp 0</span>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">Nominal Uang Terima</label>
                            <input type="number" id="uangBayar" class="form-control form-control-lg fw-bold text-success border-2" oninput="hitungKembalian()" placeholder="0">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-muted">Kembalian</span>
                            <span class="fw-bold text-dark h5 m-0" id="textKembalian">Rp 0</span>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <button class="btn btn-outline-dark w-100 rounded-pill py-2 fw-bold" onclick="resetTransaksi()">
                                <i class="fas fa-undo me-1"></i> RESET
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-pink w-100 rounded-pill py-2 fw-bold shadow-sm" onclick="konfirmasiPembayaran()">
                                <i class="fas fa-check-circle me-1"></i> KONFIRMASI
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="areaResi" style="display:none;">
    <div style="width: 300px; font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.2;">
        <div style="text-align:center; margin-bottom: 10px;">
            <h2 style="margin:0;">ZOYA KASIR</h2>
            <p style="margin:0;">Riau, Indonesia</p>
            <p style="margin:0;"><?php echo date('d-m-Y H:i:s'); ?></p>
        </div>
        <p>Plg: <span id="resiNama"></span></p>
        <p>Tlp: <span id="resiTelp"></span></p>
        <hr style="border-top: 1px dashed black;">
        <div id="resiItems"></div>
        <hr style="border-top: 1px dashed black;">
        <div style="display:flex; justify-content:space-between; font-weight:bold;">
            <span>TOTAL</span><span id="resiTotal"></span>
        </div>
        <div style="display:flex; justify-content:space-between;">
            <span>BAYAR</span><span id="resiBayar"></span>
        </div>
        <div style="display:flex; justify-content:space-between;">
            <span>KEMBALI</span><span id="resiKembali"></span>
        </div>
        <hr style="border-top: 1px dashed black;">
        <div style="text-align:center; margin-top: 10px;">
            <p>Terima Kasih!</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let keranjang = [];
let totalHarga = 0;

function tambahItem(id, nama, harga, stok) {
    let index = keranjang.findIndex(i => i.id === id);
    if (index !== -1) {
        if (keranjang[index].qty >= stok) return Swal.fire('Stok Limit!', 'Stok tersedia hanya ' + stok, 'warning');
        keranjang[index].qty++;
        keranjang[index].subtotal = keranjang[index].qty * harga;
    } else {
        keranjang.push({ id, nama, harga, qty: 1, subtotal: harga });
    }
    renderKeranjang();
}

function hapusItem(index) {
    keranjang.splice(index, 1);
    renderKeranjang();
}

function renderKeranjang() {
    const list = document.getElementById('listPesanan');
    list.innerHTML = '';
    totalHarga = 0;

    keranjang.forEach((item, i) => {
        totalHarga += item.subtotal;
        list.innerHTML += `
            <tr class="align-middle">
                <td><span class="fw-bold">${item.nama}</span><br><small class="text-muted">@${item.harga.toLocaleString()}</small></td>
                <td class="text-center">x${item.qty}</td>
                <td class="text-end fw-bold">Rp ${item.subtotal.toLocaleString()}</td>
                <td class="text-end"><button class="btn btn-sm text-danger" onclick="hapusItem(${i})"><i class="fas fa-trash"></i></button></td>
            </tr>`;
    });
    document.getElementById('textTotal').innerText = 'Rp ' + totalHarga.toLocaleString();
    hitungKembalian();
}

function hitungKembalian() {
    let bayar = parseInt(document.getElementById('uangBayar').value) || 0;
    let kembali = bayar - totalHarga;
    document.getElementById('textKembalian').innerText = 'Rp ' + (kembali > 0 ? kembali.toLocaleString() : 0);
}

function resetTransaksi() {
    keranjang = [];
    document.getElementById('namaPelanggan').value = '';
    document.getElementById('alamatPelanggan').value = '';
    document.getElementById('telpPelanggan').value = '';
    document.getElementById('uangBayar').value = '';
    renderKeranjang();
}

function konfirmasiPembayaran() {
    const nama = document.getElementById('namaPelanggan').value;
    const alamat = document.getElementById('alamatPelanggan').value;
    const telp = document.getElementById('telpPelanggan').value;
    const bayar = parseInt(document.getElementById('uangBayar').value) || 0;

    if (keranjang.length === 0) return Swal.fire('Keranjang Kosong!', 'Pilih produk dulu.', 'error');
    if (!nama || !alamat || !telp) return Swal.fire('Data Belum Lengkap!', 'Nama, Alamat, dan No.Telp wajib diisi.', 'warning');
    if (bayar < totalHarga) return Swal.fire('Uang Kurang!', 'Input nominal yang cukup.', 'error');

    fetch('simpan_transaksi.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            nama_pelanggan: nama, 
            alamat: alamat,
            no_telp: telp,
            total_harga: totalHarga, 
            items: keranjang 
        })
    })
    .then(r => r.json())
    .then(res => {
        if(res.status === 'success') {
            Swal.fire({
                title: 'Transaksi Berhasil!',
                text: 'Mencetak resi...',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                cetakResi(nama, telp, bayar);
            });
        } else {
            Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
        }
    });
}

function cetakResi(nama, telp, bayar) {
    document.getElementById('resiNama').innerText = nama;
    document.getElementById('resiTelp').innerText = telp;
    const itemsArea = document.getElementById('resiItems');
    itemsArea.innerHTML = '';
    keranjang.forEach(i => {
        itemsArea.innerHTML += `<div style="display:flex; justify-content:space-between;"><span>${i.nama} x${i.qty}</span><span>${i.subtotal.toLocaleString()}</span></div>`;
    });
    document.getElementById('resiTotal').innerText = 'Rp ' + totalHarga.toLocaleString();
    document.getElementById('resiBayar').innerText = 'Rp ' + bayar.toLocaleString();
    document.getElementById('resiKembali').innerText = document.getElementById('textKembalian').innerText;

    let isiResi = document.getElementById('areaResi').innerHTML;
    let originalBody = document.body.innerHTML;
    document.body.innerHTML = isiResi;
    window.print();
    document.body.innerHTML = originalBody;
    window.location.reload();
}
</script>

<?php include '../templates/footer.php'; ?>