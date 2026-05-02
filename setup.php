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

// Koneksi ke database yang sudah dibuat
$mysqli->select_db('keuangan_db');

// Insert user default jika belum ada
$check_user = $mysqli->query("SELECT id FROM users WHERE username = 'admin' LIMIT 1");
if ($check_user && $check_user->num_rows === 0) {
    $password_hash = password_hash('admin123', PASSWORD_BCRYPT);
    $mysqli->query("INSERT INTO users (username, password, email, nama_lengkap) VALUES ('admin', '$password_hash', 'admin@example.com', 'Administrator')");
}

$mysqli->close();

echo "<h2 style='color: green; font-family: Arial;'>✓ Database dan tabel berhasil dibuat!</h2>";
echo "<p style='font-family: Arial;'><strong>User default:</strong></p>";
echo "<ul style='font-family: Arial;'>";
echo "<li>Username: <code>admin</code></li>";
echo "<li>Password: <code>admin123</code></li>";
echo "</ul>";
echo "<p style='font-family: Arial;'><a href='login.php'>Klik di sini untuk login</a></p>";
?>
