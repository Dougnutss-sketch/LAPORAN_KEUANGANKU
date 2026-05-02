<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';
requireLogin();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $tipe = $_POST['tipe'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (!in_array($tipe, ['pemasukan', 'pengeluaran'], true)) {
        $errors[] = 'Pilih jenis transaksi yang valid.';
    }
    if ($tanggal === '') {
        $errors[] = 'Tanggal harus diisi.';
    }
    if ($jumlah === '' || !is_numeric($jumlah) || floatval($jumlah) <= 0) {
        $errors[] = 'Jumlah harus berupa angka lebih dari 0.';
    }
    if ($keterangan === '') {
        $errors[] = 'Keterangan harus diisi.';
    }

    if (empty($errors)) {
        if ($id > 0) {
            $stmt = $mysqli->prepare('UPDATE transaksi SET tipe = ?, tanggal = ?, jumlah = ?, keterangan = ? WHERE id = ?');
            $stmt->bind_param('ssdsi', $tipe, $tanggal, $jumlah, $keterangan, $id);
            $success = $stmt->execute();
            $stmt->close();
            $_SESSION['message'] = $success ? 'Transaksi berhasil diperbarui.' : 'Gagal memperbarui transaksi.';
        } else {
            $stmt = $mysqli->prepare('INSERT INTO transaksi (tipe, tanggal, jumlah, keterangan) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssds', $tipe, $tanggal, $jumlah, $keterangan);
            $success = $stmt->execute();
            $stmt->close();
            $_SESSION['message'] = $success ? 'Transaksi berhasil disimpan.' : 'Gagal menyimpan transaksi.';
        }

        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($action === 'delete' && $id > 0) {
        $stmt = $mysqli->prepare('DELETE FROM transaksi WHERE id = ?');
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = $success ? 'Transaksi berhasil dihapus.' : 'Gagal menghapus transaksi.';
        header('Location: index.php');
        exit;
    }

    if ($action === 'edit' && $id > 0) {
        $stmt = $mysqli->prepare('SELECT id, tipe, tanggal, jumlah, keterangan FROM transaksi WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $editData = $result->fetch_assoc();
        $stmt->close();
    }
}

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

$sql = 'SELECT id, tipe, tanggal, jumlah, keterangan FROM transaksi';
if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY tanggal DESC, id DESC';

$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die('Query gagal: ' . $mysqli->error);
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$transactions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totals = ['pemasukan' => 0.00, 'pengeluaran' => 0.00];
foreach ($transactions as $row) {
    if ($row['tipe'] === 'pemasukan') {
        $totals['pemasukan'] += floatval($row['jumlah']);
    } else {
        $totals['pengeluaran'] += floatval($row['jumlah']);
    }
}
$saldo = $totals['pemasukan'] - $totals['pengeluaran'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Keuangan</title>
    <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div>
                <h1>Manajemen Keuangan</h1>
                <p>Kelola pemasukan dan pengeluaran pribadi Anda.</p>
            </div>
            <div class="nav-actions">
                <span class="user-badge">Hi, <?= htmlspecialchars($_SESSION['user']) ?></span>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </header>

        <?php if ($message): ?>
            <div class="alert alert-success" id="toast" data-message="<?= sanitize($message) ?>"><?= sanitize($message) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error" id="toast" data-message="<?= sanitize(implode(' ', $errors)) ?>">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= sanitize($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php include __DIR__ . '/pages/transaction_form.php'; ?>
        <?php include __DIR__ . '/pages/transaction_list.php'; ?>
    </div>

    <script src="assets/app.js"></script>
</body>
</html>
