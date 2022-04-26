<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Penjual</h1>

  <div class="row">

    <div class="col-sm-12">
        <a href="<?= base_url('seller/form/') ?>" class="btn btn-primary">Tambah Penjual</a>
    </div>

    <div class="col-sm-6 mt-3">
      <?php if ($this->session->flashdata('message')) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('message') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php endif; ?>
    </div>

    <div class="col-sm-12">
    <div class="card">
        <div class="card-body">
          <table class="table table-hover" id="table" data-url="<?php echo base_url('seller/get_list'); ?>">
            <thead>
                <tr>
                    <th class="default-sort" data-sort="asc">Nama Toko</th>
                    <th>No Telp</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th class="no-sort text-center">Opsi</th>
                </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

</div>
<!-- /.container-fluid -->