<?php
// koneksi.php

// Ambil Connection String dari Environment Variables Azure
// Nama variabelnya harus sama dengan yang Anda set di Azure: MYSQL_CONNECTION
$connection_string = getenv("MYSQL_CONNECTION");

// Periksa apakah connection string ditemukan
if (!$connection_string) {
    die("Error: MYSQL_CONNECTION environment variable not found. Pastikan sudah diatur di Azure App Service.");
}

// Parsing Connection String MySQL
// Format standard: Server=server_name.mysql.database.azure.com;Database=db_name;Uid=user@server_name;Pwd=password;
parse_str(str_replace(['=', ';'], ['&', '&'], $connection_string), $params);

// Ambil nilai-nilai yang diperlukan (sesuaikan parsing jika format berbeda)
$host = $params['Server'];
$database = $params['Database'];
$user = $params['Uid'];
$password = $params['Pwd'];
$port = 3306; // Port default MySQL

// Coba buat koneksi
$koneksi = new mysqli($host, $user, $password, $database, $port);

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
// echo "Koneksi ke Azure Database for MySQL berhasil!";
?>