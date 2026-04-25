<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Detail</h5>
                <small class="text-muted d-block mb-3">
                    Tutorial: <strong><?= esc($tutorial['judul']) ?></strong> | 
                    Langkah #<?= $detail['order'] ?>
                </small>

                <?php if (session()->getFlashdata('failed')) { ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('failed') ?></div>
                <?php } ?>

                <?= form_open_multipart('detail/update/' . $detail['id']) ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipe Konten</label>
                        <select name="type" id="type-select" class="form-select" required>
                            <option value="text" <?= $detail['type'] == 'text' ? 'selected' : '' ?>>Text</option>
                            <option value="image" <?= $detail['type'] == 'image' ? 'selected' : '' ?>>Gambar</option>
                            <option value="code" <?= $detail['type'] == 'code' ? 'selected' : '' ?>>Code</option>
                            <option value="url" <?= $detail['type'] == 'url' ? 'selected' : '' ?>>URL</option>
                        </select>
                        <small class="text-muted">Ganti tipe akan mengubah konten sesuai tipe baru</small>
                    </div>

                    <!-- TEXT FIELD -->
                    <div class="mb-3 type-field" data-type="text" style="<?= $detail['type'] != 'text' ? 'display:none' : '' ?>">
                        <label class="form-label">Text Content</label>
                        <textarea name="text" class="form-control tinymce-editor" rows="4"><?= esc($detail['text'] ?? '') ?></textarea>
                    </div>

                    <!-- IMAGE FIELD -->
                    <div class="mb-3 type-field" data-type="image" style="<?= $detail['type'] != 'image' ? 'display:none' : '' ?>">
                        <label class="form-label">Gambar</label>
                        <?php if (!empty($detail['gambar'])) { ?>
                            <div class="mb-2">
                                <img src="<?= base_url('uploads/' . $detail['gambar']) ?>" 
                                     class="img-fluid rounded" style="max-height: 200px;">
                                <small class="d-block text-muted">Gambar saat ini: <?= esc($detail['gambar']) ?></small>
                            </div>
                        <?php } ?>
                        <input type="file" name="gambar" class="form-control" 
                               accept="image/jpeg,image/png,image/gif,image/webp">
                        <small class="text-muted">
                            Kosongkan kalau tidak mau ganti gambar. Format: JPG, PNG, GIF, WebP. Max 2MB.
                        </small>
                    </div>

                    <!-- CODE FIELD -->
                    <div class="mb-3 type-field" data-type="code" style="<?= $detail['type'] != 'code' ? 'display:none' : '' ?>">
                        <div class="row">
                            <div class="col-md-9 mb-3">
                                <label class="form-label">Code</label>
                                <textarea name="code" class="form-control font-monospace" rows="8"><?= esc($detail['code'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Language</label>
                                <select name="code_language" class="form-select">
                                    <?php 
                                    $langs = ['plaintext', 'php', 'javascript', 'html', 'css', 'python', 'sql', 'json', 'bash'];
                                    foreach ($langs as $lang) { ?>
                                        <option value="<?= $lang ?>" 
                                            <?= ($detail['code_language'] ?? 'plaintext') == $lang ? 'selected' : '' ?>>
                                            <?= ucfirst($lang) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- URL FIELD -->
                    <div class="mb-3 type-field" data-type="url" style="<?= $detail['type'] != 'url' ? 'display:none' : '' ?>">
                        <label class="form-label">URL</label>
                        <input type="url" name="url" class="form-control" value="<?= esc($detail['url'] ?? '') ?>">
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <a href="<?= base_url('tutorial/detail/' . $tutorial['id']) ?>" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>

                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page-script') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type-select');
    const typeFields = document.querySelectorAll('.type-field');
    
    typeSelect.addEventListener('change', function() {
        const selected = this.value;
        typeFields.forEach(field => {
            field.style.display = field.dataset.type === selected ? 'block' : 'none';
        });
    });
});
</script>
<?= $this->endSection() ?>
