<section class="card card-form">
    <div class="card-header">
        <h2><?= $editData ? 'Edit Transaksi' : 'Tambah Transaksi' ?></h2>
    </div>

    <form method="post" id="transaksiForm" class="form-grid">
        <input type="hidden" name="id" value="<?= $editData ? intval($editData['id']) : 0 ?>">

        <label for="tipe">Tipe Transaksi</label>
        <select name="tipe" id="tipe" required>
            <option value="pemasukan" <?= $editData && $editData['tipe'] === 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
            <option value="pengeluaran" <?= $editData && $editData['tipe'] === 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
        </select>

        <label for="tanggal">Tanggal</label>
        <input type="date" id="tanggal" name="tanggal" value="<?= $editData ? sanitize($editData['tanggal']) : date('Y-m-d') ?>" required>

        <label for="jumlah">Jumlah (Rp)</label>
        <input type="number" id="jumlah" name="jumlah" min="1" step="0.01" value="<?= $editData ? sanitize($editData['jumlah']) : '' ?>" required>

        <label for="keterangan">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="4" required><?= $editData ? sanitize($editData['keterangan']) : '' ?></textarea>

        <div class="form-actions form-actions-full">
            <?php if ($editData): ?>
                <a href="index.php" class="btn btn-muted">Batal</a>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary"><?= $editData ? 'Perbarui' : 'Simpan' ?></button>
        </div>
    </form>
</section>
