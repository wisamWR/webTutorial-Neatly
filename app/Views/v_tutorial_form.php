<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?= $action == 'create' ? 'Tambah Tutorial Baru' : 'Edit Tutorial' ?>
                </h5>

                <?php if (session()->getFlashdata('failed')) { ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('failed') ?>
                    </div>
                <?php } ?>

                <?php
                $formAction = $action == 'create' ? 'tutorial/store' : 'tutorial/update/' . $tutorial['id'];
                $judulValue = old('judul', $tutorial['judul'] ?? '');
                $matkulValue = old('kode_matkul', $tutorial['kode_matkul'] ?? '');

                $judul = [
                    'name' => 'judul',
                    'id' => 'judul',
                    'class' => 'form-control',
                    'placeholder' => 'Misal: Hello World dengan PHP',
                    'value' => $judulValue,
                ];
                ?>

                <?= form_open($formAction, 'class="row g-3"') ?>

                    <div class="col-12">
                        <label for="judul" class="form-label">Judul Tutorial <span class="text-danger">*</span></label>
                        <?= form_input($judul) ?>
                        <small class="text-muted">Judul akan digunakan untuk generate URL Presentation & Finished</small>
                    </div>

                    <div class="col-12 mt-3">
                        <label for="kode_matkul" class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                        <select name="kode_matkul" id="kode_matkul" class="form-select" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php foreach ($makul_list as $makul) { ?>
                                <option value="<?= $makul['kdmk'] ?>" 
                                    <?= $matkulValue == $makul['kdmk'] ? 'selected' : '' ?>>
                                    <?= $makul['kdmk'] ?> - <?= $makul['nama'] ?>
                                </option>
                            <?php } ?>
                        </select>
                        <?php if (empty($makul_list)) { ?>
                            <small class="text-danger">
                                Gagal mengambil data mata kuliah. Coba logout & login ulang.
                            </small>
                        <?php } ?>
                    </div>

                    <?php if ($action == 'edit') { ?>
                        <div class="col-12 mt-3">
                            <div class="alert alert-info small mb-0">
                                <strong>Info:</strong> URL Presentation dan URL Finished tidak akan berubah
                                saat edit, supaya link yang sudah dishare tetap valid.
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-12 d-flex gap-2 mt-4">
                        <?= form_submit('submit', $action == 'create' ? 'Simpan' : 'Update', ['class' => 'btn btn-primary']) ?>
                        <a href="<?= base_url('tutorial') ?>" class="btn btn-secondary">Batal</a>
                    </div>

                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
