<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<!-- Header Info Card -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="card-title mb-1"><?= esc($tutorial['judul']) ?></h5>
                        <small class="text-muted">
                            <?= esc($tutorial['kode_matkul']) ?> - <?= esc($tutorial['nama_matkul']) ?>
                        </small>
                    </div>
                    <div>
                        <a href="<?= base_url('tutorial') ?>" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= base_url('presentation/' . $tutorial['url_presentation']) ?>" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-eye"></i> Preview Presentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<!-- Form Add Detail Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tambah Detail Baru</h5>
                
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-text" type="button">
                            <i class="bi bi-text-paragraph"></i> Text
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-image" type="button">
                            <i class="bi bi-image"></i> Gambar
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-code" type="button">
                            <i class="bi bi-code-slash"></i> Code
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-url" type="button">
                            <i class="bi bi-link-45deg"></i> URL
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content pt-3">
                    
                    <!-- TEXT TAB -->
                    <div class="tab-pane fade show active" id="tab-text" role="tabpanel">
                        <?= form_open('detail/store/' . $tutorial['id']) ?>
                            <input type="hidden" name="type" value="text">
                            <div class="mb-3">
                                <label class="form-label">Text Content</label>
                                <textarea name="text" class="form-control tinymce-editor" rows="4" 
                                          placeholder="Misal: Start module apache pada XAMPP"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Tambah Text
                            </button>
                        <?= form_close() ?>
                    </div>
                    
                    <!-- IMAGE TAB -->
                    <div class="tab-pane fade" id="tab-image" role="tabpanel">
                        <?= form_open_multipart('detail/store/' . $tutorial['id']) ?>
                            <input type="hidden" name="type" value="image">
                            <div class="mb-3">
                                <label class="form-label">Upload Gambar</label>
                                <input type="file" name="gambar" class="form-control" 
                                       accept="image/jpeg,image/png,image/gif,image/webp" required>
                                <small class="text-muted">Format: JPG, PNG, GIF, WebP. Maksimal 2MB.</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Upload & Tambah
                            </button>
                        <?= form_close() ?>
                    </div>
                    
                    <!-- CODE TAB -->
                    <div class="tab-pane fade" id="tab-code" role="tabpanel">
                        <?= form_open('detail/store/' . $tutorial['id']) ?>
                            <input type="hidden" name="type" value="code">
                            <div class="row">
                                <div class="col-md-9 mb-3">
                                    <label class="form-label">Code</label>
                                    <textarea name="code" class="form-control font-monospace" rows="8" 
                                              placeholder="<?php echo 'Hello World!'; ?>" required></textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Language</label>
                                    <select name="code_language" class="form-select">
                                        <option value="plaintext">Plain Text</option>
                                        <option value="php" selected>PHP</option>
                                        <option value="javascript">JavaScript</option>
                                        <option value="html">HTML</option>
                                        <option value="css">CSS</option>
                                        <option value="python">Python</option>
                                        <option value="sql">SQL</option>
                                        <option value="json">JSON</option>
                                        <option value="bash">Bash</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Tambah Code
                            </button>
                        <?= form_close() ?>
                    </div>
                    
                    <!-- URL TAB -->
                    <div class="tab-pane fade" id="tab-url" role="tabpanel">
                        <?= form_open('detail/store/' . $tutorial['id']) ?>
                            <input type="hidden" name="type" value="url">
                            <div class="mb-3">
                                <label class="form-label">URL</label>
                                <input type="url" name="url" class="form-control" 
                                       placeholder="https://example.com" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Tambah URL
                            </button>
                        <?= form_close() ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- List Detail Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Detail Tutorial (<?= count($details) ?> langkah)</h5>
                
                <?php if (empty($details)) { ?>
                    <div class="alert alert-info">
                        Belum ada detail. Tambahkan detail pertama di form atas.
                    </div>
                <?php } else { ?>
                    <?php foreach ($details as $i => $d) { ?>
                        <div class="detail-item border rounded p-3 mb-3 <?= $d['status'] == 'hide' ? 'bg-light' : '' ?>">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary">#<?= $d['order'] ?></span>
                                    <span class="badge bg-secondary"><?= strtoupper($d['type']) ?></span>
                                    <?php if ($d['status'] == 'show') { ?>
                                        <span class="badge bg-success">SHOW</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">HIDE</span>
                                    <?php } ?>
                                </div>
                                <div class="d-flex gap-1 flex-wrap">
                                    <!-- Reorder buttons -->
                                    <?php if ($i > 0) { ?>
                                        <a href="<?= base_url('detail/move/' . $d['id'] . '/up') ?>" 
                                           class="btn btn-sm btn-outline-secondary" title="Pindah ke atas">
                                            <i class="bi bi-arrow-up"></i>
                                        </a>
                                    <?php } ?>
                                    <?php if ($i < count($details) - 1) { ?>
                                        <a href="<?= base_url('detail/move/' . $d['id'] . '/down') ?>" 
                                           class="btn btn-sm btn-outline-secondary" title="Pindah ke bawah">
                                            <i class="bi bi-arrow-down"></i>
                                        </a>
                                    <?php } ?>
                                    <!-- Toggle show/hide -->
                                    <a href="<?= base_url('detail/toggle/' . $d['id']) ?>" 
                                       class="btn btn-sm <?= $d['status'] == 'show' ? 'btn-warning' : 'btn-success' ?>" 
                                       title="Toggle status">
                                        <i class="bi <?= $d['status'] == 'show' ? 'bi-eye-slash' : 'bi-eye' ?>"></i>
                                    </a>
                                    <!-- Edit -->
                                    <a href="<?= base_url('detail/edit/' . $d['id']) ?>" 
                                       class="btn btn-sm btn-info" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <!-- Delete -->
                                    <a href="<?= base_url('detail/delete/' . $d['id']) ?>" 
                                       class="btn btn-sm btn-danger delete-detail-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="detail-content mt-2">
                                <?php if ($d['type'] == 'text') { ?>
                                    <div class="mb-0 rich-text-content"><?= $d['text'] ?></div>
                                <?php } elseif ($d['type'] == 'image' && $d['gambar']) { ?>
                                    <img src="<?= base_url('uploads/' . $d['gambar']) ?>" 
                                         alt="Detail image" 
                                         class="img-fluid rounded" 
                                         style="max-height: 300px;">
                                <?php } elseif ($d['type'] == 'code') { ?>
                                    <pre class="bg-dark text-light p-3 rounded mb-0"><code><?= esc($d['code']) ?></code></pre>
                                    <?php if (!empty($d['code_language'])) { ?>
                                        <small class="text-muted">Language: <?= esc($d['code_language']) ?></small>
                                    <?php } ?>
                                <?php } elseif ($d['type'] == 'url') { ?>
                                    <a href="<?= esc($d['url']) ?>" target="_blank" rel="noopener">
                                        <i class="bi bi-link-45deg"></i> <?= esc($d['url']) ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page-script') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    document.querySelectorAll('.delete-detail-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Yakin ingin menghapus detail ini? File gambar terkait juga akan dihapus.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
