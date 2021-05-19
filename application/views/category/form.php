<!-- Begin Page Content -->
<div class="container-fluid">
<?php $user = $this->session->userdata('user'); ?>

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $deskripsi ?></h1>

  <div class="row">
    <div class="col-sm-12">
      <a href="<?= base_url('categories/') ?>" class="btn btn-primary">Kembali</a>
    </div>


    <div class="col-sm-6 mt-3">
      <?php if ($error != '') : ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $error ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php endif; ?>
    </div>


    <div class="col-sm-12">
      <form action="<?= $data != '' ? base_url('categories/form/' . $data->id) : base_url('categories/form/') ?>" method="POST">
        <div class="card">
          <div class="card-body">
            <div class="row form-group">
              <label for="name" class="col-sm-2">Nama Kategori</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="name" name="name" required value="<?= $data != '' ? $data->name : set_value('name') ?>">
                <?= form_error('name') ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="status" class="col-sm-2">Status</label>
              <div class="col-sm-5">
                <select name="status" id="status" class="form-control">
                  <option value="1" <?= $data != '' ? $data->status == 1 ? 'selected' : '' : set_value('status') == 1 ? 'selected' : '' ?>>Aktif</option>
                  <option value="0" <?= $data != '' ? $data->status == 0 ? 'selected' : '' : set_value('status') == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>


  </div>

</div>
<!-- /.container-fluid -->