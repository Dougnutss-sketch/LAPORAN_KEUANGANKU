document.addEventListener('DOMContentLoaded', function () {
    const deleteLinks = document.querySelectorAll('.btn-delete');
    deleteLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            const confirmed = confirm('Yakin ingin menghapus transaksi ini?');
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });

    const form = document.getElementById('transaksiForm');
    if (form) {
        form.addEventListener('submit', function (event) {
            const jumlah = document.getElementById('jumlah').value.trim();
            const keterangan = document.getElementById('keterangan').value.trim();
            const tanggal = document.getElementById('tanggal').value.trim();

            if (!tanggal || !jumlah || !keterangan) {
                alert('Semua field harus diisi.');
                event.preventDefault();
                return;
            }
            if (isNaN(jumlah) || Number(jumlah) <= 0) {
                alert('Jumlah harus berupa angka lebih dari 0.');
                event.preventDefault();
            }
        });
    }

    const toast = document.getElementById('toast');
    if (toast) {
        setTimeout(function () {
            toast.style.opacity = '0';
            setTimeout(function () {
                toast.remove();
            }, 400);
        }, 4000);
    }
});
