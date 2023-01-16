<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-8">
            <h2 class="my-3">Form ubah data komik</h2>
            <?= ($validation->hasError('judul'))?dd($validation->listErrors()):'none'; ?>
            <form action="<?=site_url('/komik/update/'.$komik['id'])?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="slug" value="<?= $komik['slug']; ?>">
                <input type="hidden" name="sampulLama" value="<?= $komik['sampul']; ?>">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control <?= ($validation->hasError('judul'))?'is-invalid':''; ?>" id="judul" name="judul" autofocus value="<?= old('judul')?old('judul'): $komik['judul']; ?>">
                    <div class="invalid-feedback"><?= $validation->getError('judul'); ?></div>
                </div>
                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" class="form-control <?= ($validation->hasError('penulis'))?'is-invalid':''; ?>" id="penulis" name="penulis" value="<?= old('penulis')?old('penulis'): $komik['penulis']; ?>">
                    <div class="invalid-feedback"><?= $validation->getError('penulis'); ?></div>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control <?= ($validation->hasError('penerbit'))?'is-invalid':''; ?>" id="penerbit" name="penerbit" value="<?= old('penerbit')?old('penerbit'): $komik['penerbit']; ?>">
                    <div class="invalid-feedback"><?= $validation->getError('penerbit'); ?></div>
                </div>
                <div class="mb-3">
                <label for="sampul" class="form-label">Sampul</label>
                    <div class="">
                        <img src="/img/<?= $komik['sampul']; ?>" class="img-thumbnail img-preview" width="200">
                    </div>
                    <input class="form-control custom-file-input <?= ($validation->hasError('sampul'))?'is-invalid':''; ?>" type="file" id="sampul" name="sampul">
                    <div class="invalid-feedback"><?= $validation->getError('sampul'); ?></div>
                </div>
                <button type="submit" class="btn btn-primary">Ubah data</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>