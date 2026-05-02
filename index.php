<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$message = '';
$errors = [];
$editData = null;

$filterFrom = $_GET['from'] ?? '';
$filterTo = $_GET['to'] ?? '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

function sanitize($value)
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// ==========================
// INSERT / UPDATE
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $tipe = $_POST['tipe'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (!in_array($tipe, ['pemasukan', 'pengeluaran'])) {
        $errors[] = 'Tipe tidak valid';
    }
    if ($tanggal === '') {
        $errors[] = 'Tanggal wajib diisi';
    }
    if (!is_numeric($jumlah) || $jumlah <= 0) {
        $errors[] = 'Jumlah harus angka > 0';
    }

    if (empty($errors)) {
        if ($id > 0) {
            if ($role === 'admin') {
                $stmt = $mysqli->prepare("UPDATE transaksi SET tipe=?, tanggal=?, jumlah=?, keterangan=? WHERE id=?");
                $stmt->bind_param('ssdsi', $tipe, $tanggal, $jumlah, $keterangan, $id);
            } else {
                $stmt = $mysqli->prepare("UPDATE transaksi SET tipe=?, tanggal=?, jumlah=?, keterangan=? WHERE id=? AND user_id=?");
                $stmt->bind_param('ssdsi', $tipe, $tanggal, $jumlah, $keterangan, $id, $user_id);
            }
        } else {
            $stmt = $mysqli->prepare("INSERT INTO transaksi (tipe, tanggal, jumlah, keterangan, user_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssdsi', $tipe, $tanggal, $jumlah, $keterangan, $user_id);
        }

        $success = $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = $success ? 'Berhasil disimpan' : 'Gagal disimpan';
        header('Location: index.php');
        exit;
    }
}

// ==========================
// DELETE / EDIT
// ==========================
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);

    if ($_GET['action'] === 'delete') {
        if ($role === 'admin') {
            $stmt = $mysqli->prepare("DELETE FROM transaksi WHERE id=?");
            $stmt->bind_param('i', $id);
        } else {
            $stmt = $mysqli->prepare("DELETE FROM transaksi WHERE id=? AND user_id=?");
            $stmt->bind_param('ii', $id, $user_id);
        }

        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = 'Data dihapus';
        header('Location: index.php');
        exit;
    }

    if ($_GET['action'] === 'edit') {
        if ($role === 'admin') {
            $stmt = $mysqli->prepare("SELECT * FROM transaksi WHERE id=?");
            $stmt->bind_param('i', $id);
        } else {
            $stmt = $mysqli->prepare("SELECT * FROM transaksi WHERE id=? AND user_id=?");
            $stmt->bind_param('ii', $id, $user_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $editData = $result->fetch_assoc();
        $stmt->close();
    }
}

// ==========================
// SELECT DATA
// ==========================
$where = [];
$params = [];
$types = '';

if ($filterFrom !== '') {
    $where[] = 'tanggal >= ?';
    $params[] = $filterFrom;
    $types .= 's';
}
if ($filterTo !== '') {
    $where[] = 'tanggal <= ?';
    $params[] = $filterTo;
    $types .= 's';
}

if ($role !== 'admin') {
    $where[] = 'user_id = ?';
    $params[] = $user_id;
    $types .= 'i';
}

$sql = "SELECT id, tipe, tanggal, jumlah, keterangan FROM transaksi";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY tanggal DESC";

$stmt = $mysqli->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$transactions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ==========================
// TOTAL
// ==========================
$masuk = 0;
$keluar = 0;

foreach ($transactions as $t) {
    if ($t['tipe'] === 'pemasukan') $masuk += $t['jumlah'];
    else $keluar += $t['jumlah'];
}

$saldo = $masuk - $keluar;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Keuangan</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<div class="app-shell">

<header class="app-header">
    <div>
        <h1>Manajemen Keuangan</h1>
        <p>Kelola pemasukan dan pengeluaran</p>
    </div>

    <div class="nav-actions">
        <span class="user-badge">
            Hi, <?= htmlspecialchars($username) ?> (<?= $role ?>)
        </span>
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
</header>

<?php if ($message): ?>
    <div class="alert alert-success"><?= sanitize($message) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= sanitize($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/pages/transaction_form.php'; ?>
<?php include __DIR__ . '/pages/transaction_list.php'; ?>

</div>

</body>
</html>