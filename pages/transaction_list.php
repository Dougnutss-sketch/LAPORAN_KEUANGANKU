<section class="dashboard-grid">
    <article class="card card-balance">
        <small>Saldo Akhir</small>
        <h2>Rp<?= number_format($saldo, 2, ',', '.') ?></h2>
    </article>
    <article class="card card-income">
        <small>Total Pemasukan</small>
        <h2>Rp<?= number_format($totals['pemasukan'], 2, ',', '.') ?></h2>
    </article>
    <article class="card card-expense">
        <small>Total Pengeluaran</small>
        <h2>Rp<?= number_format($totals['pengeluaran'], 2, ',', '.') ?></h2>
    </article>
</section>

<section class="card card-table">
    <div class="table-header">
        <h2>Daftar Transaksi</h2>
        <form method="get" class="filter-form">
            <div>
                <label>Dari</label>
                <input type="date" name="from" value="<?= sanitize($filterFrom) ?>">
            </div>
            <div>
                <label>Sampai</label>
                <input type="date" name="to" value="<?= sanitize($filterTo) ?>">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="index.php" class="btn btn-muted">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada transaksi.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $row): ?>
                        <tr>
                            <td><?= sanitize($row['tanggal']) ?></td>
                            <td class="type-<?= sanitize($row['tipe']) ?>"><?= $row['tipe'] === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' ?></td>
                            <td>Rp<?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                            <td><?= sanitize($row['keterangan']) ?></td>
                            <td class="actions-cell">
                                <a href="index.php?action=edit&id=<?= intval($row['id']) ?>" class="btn btn-edit">Edit</a>
                                <a href="index.php?action=delete&id=<?= intval($row['id']) ?>" class="btn btn-delete">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
