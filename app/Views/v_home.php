<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Selamat Datang</h5>
                <p>
                    Anda login sebagai <strong><?= session()->get('email') ?? 'Guest' ?></strong>.
                </p>
                <p>
                    Gunakan menu <strong>Tutorial</strong> di sidebar untuk mengelola data tutorial.
                    Tutorial yang dibuat akan memiliki dua URL publik:
                </p>
                <ul>
                    <li><strong>URL Presentation</strong> untuk ditampilkan saat mengajar di lab (hanya detail dengan status <em>show</em>, auto-refresh).</li>
                    <li><strong>URL Finished</strong> versi lengkap dalam format PDF untuk diakses mahasiswa di rumah.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
