<?php
// index.php
require 'koneksi.php'; // Hubungkan ke file koneksi

// --- Logika CREATE ---
if (isset($_POST['tambah_data'])) {
    $nama = $_POST['nama'];
    $usia = $_POST['usia'];

    $sql = "INSERT INTO pengguna (nama, usia) VALUES ('$nama', '$usia')";
    if ($koneksi->query($sql) === TRUE) {
        $pesan = "Data berhasil ditambahkan!";
    } else {
        $pesan = "Error: " . $sql . "<br>" . $koneksi->error;
    }
}

// --- Logika DELETE ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql = "DELETE FROM pengguna WHERE id=$id";
    if ($koneksi->query($sql) === TRUE) {
        $pesan = "Data berhasil dihapus!";
    } else {
        $pesan = "Error menghapus data: " . $koneksi->error;
    }
    // Redirect untuk menghindari pengiriman ulang form
    header("Location: index.php");
    exit();
}

// --- Logika READ ---
$sql_read = "SELECT * FROM pengguna ORDER BY id DESC";
$result = $koneksi->query($sql_read);

// Cek apakah tabel 'pengguna' ada, jika tidak, buat
if (!$result) {
    $sql_create_table = "CREATE TABLE pengguna (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(30) NOT NULL,
        usia INT(3) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    if ($koneksi->query($sql_create_table) === TRUE) {
        $pesan = "Tabel 'pengguna' berhasil dibuat. Silakan tambahkan data.";
        $result = $koneksi->query($sql_read); // Coba baca lagi
    } else {
        $pesan = "Error membuat tabel: " . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Sederhana PHP Azure</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        form { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
        input[type="text"], input[type="number"] { padding: 5px; margin-right: 10px; }
        .btn-delete { background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Aplikasi CRUD Pengguna (PHP + Azure MySQL)</h2>

    <?php if (isset($pesan)): ?>
        <p class="<?= (strpos($pesan, 'Error') !== false) ? 'error' : 'success'; ?>"><?= $pesan; ?></p>
    <?php endif; ?>

    <h3>Tambah Pengguna Baru</h3>
    <form method="POST" action="">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="number" name="usia" placeholder="Usia" required>
        <button type="submit" name="tambah_data">Tambah Data</button>
    </form>

    <h3>Daftar Pengguna</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Usia</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['usia']; ?></td>
                    <td>
                        <a href="index.php?hapus=<?= $row['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php 
                endwhile;
            else: ?>
                <tr>
                    <td colspan="4">Tidak ada data pengguna.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$koneksi->close(); // Tutup koneksi setelah selesai
?>