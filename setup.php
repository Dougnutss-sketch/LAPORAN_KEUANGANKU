<?php
// Script setup untuk membuat database dan tabel

$host = '127.0.0.1';
$user = 'root';
$pass = '';

// Koneksi tanpa database terlebih dahulu
$mysqli = new mysqli($host, $user, $pass);

if ($mysqli->connect_errno) {
    die('Gagal koneksi MySQL: ' . $mysqli->connect_error);
}

// Baca file database.sql
$sql_file = file_get_contents(__DIR__ . '/database.sql');

// Pisahkan query
$queries = array_filter(array_map('trim', explode(';', $sql_file)));

// Jalankan setiap query
foreach ($queries as $query) {
    if (!empty($query)) {
        if (!$mysqli->query($query)) {
            die('Error: ' . $mysqli->error);
        }
    }
}

$mysqli->close();

echo "<h2 style='color: green; font-family: Arial;'>✓ Database dan tabel berhasil dibuat!</h2>";
echo "<p style='font-family: Arial;'><a href='index.php'>Klik di sini untuk lanjut ke aplikasi</a></p>";
?>
