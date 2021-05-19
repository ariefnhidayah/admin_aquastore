<style>
.modal-dialog {
    max-width: 600px;
    margin: 1.75rem auto;
}
</style>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Permintaan Saldo Seller</h1>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="table" data-url="<?= base_url('balance_user/get_list') ?>">
                    <thead>
                        <tr>
                            <th class="default-sort" data-sort="desc">Tanggal</th>
                            <th>Nama Seller</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="no-sort">Catatan</th>
                            <th class="no-sort">Opsi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="acceptModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="acceptModalLabel">Request Saldo Seller</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="<?= base_url('balance_user/accepted') ?>" method="post" id="form_accepted" onsubmit="accept(event, this)">
        <div class="modal-body">
            <input type="hidden" name="id">
            <div class="form-group row">
                <label class="col-md-4">Bank</label>
                <div class="col-md-8 font-weight-bold">
                    <span id="bank_name"></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Account Number</label>
                <div class="col-md-8 font-weight-bold">
                    <span id="account_number"></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Account Holder</label>
                <div class="col-md-8 font-weight-bold">
                    <span id="account_holder"></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Confirm Payment</label>
                <div class="col-md-8">
                    <div id="image_cover">
                        <input type="file" name="image" accept="image/*" id="image" onchange="changeImage(event)">
                    </div>
                    <input type="hidden" name="confirm_image" id="confirm_image">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>