<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-end mb-3">
    <a href="<?= base_url('tutorial/create') ?>" class="btn btn-primary">Tambah Tutorial Baru</a>
</div>

<?php if (session()->getFlashdata('success')) { ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php } ?>

<?php if (session()->getFlashdata('failed')) { ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('failed') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php } ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Daftar Master Tutorial</h5>
        <table class="table datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Kode Matkul</th>
                    <th>URL Presentation</th>
                    <th>URL Finished</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tutorials as $i => $t) { ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($t['judul']) ?></td>
                        <td><?= esc($t['kode_matkul']) ?> - <?= esc($t['nama_matkul']) ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary copy-url" 
                                data-url="<?= base_url('presentation/' . $t['url_presentation']) ?>" 
                                title="Copy URL">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary copy-url" 
                                data-url="<?= base_url('finished/' . $t['url_finished']) ?>" 
                                title="Copy URL">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </td>
                        <td><?= $t['created_at'] ?></td>
                        <td>
                            <a href="<?= base_url('tutorial/detail/' . $t['id']) ?>" 
                               class="btn btn-sm btn-info" title="Kelola Detail">
                                <i class="bi bi-list-ul"></i>
                            </a>
                            <a href="<?= base_url('tutorial/edit/' . $t['id']) ?>" 
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= base_url('tutorial/delete/' . $t['id']) ?>" 
                               class="btn btn-sm btn-danger delete-btn" 
                               data-title="<?= esc($t['judul']) ?>" 
                               title="Delete">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page-script') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy URL buttons
    document.querySelectorAll('.copy-url').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            navigator.clipboard.writeText(url).then(() => {
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check"></i> Copied!';
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-success');
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-primary');
                }, 2000);
            });
        });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const title = this.dataset.title;
            if (!confirm(`Yakin ingin menghapus tutorial "${title}"? Semua detail juga akan terhapus.`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
