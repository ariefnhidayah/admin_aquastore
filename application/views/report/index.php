<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan</h1>

    <div class="col-sm-12 mb-5">
            <div class="card">
                <div class="card-body">
                    <form id="filter-form">
                        <div class="col-sm-5">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Filter By:</label>
                                        <select id="filter-by" class="form-control bootstrap-select">
                                            <option value="today">Hari Ini</option>
                                            <option value="yesterday">Kemarin</option>
                                            <option value="this month">Bulan Ini</option>
                                            <option value="last month">Bulan Lalu</option>
                                            <option value="this year">Tahun Ini</option>
                                            <option value="99">Kostum</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Dari:</label>
                                        <input type="text" class="form-control" readonly="" name="from" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-4 no-padding-right">
                                    <div class="form-group">
                                        <label class="control-label">Sampai:</label>
                                        <input type="text" class="form-control" readonly="" name="to" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2 col-xs-6">
                                    <button class="btn btn-primary" type="button" id="filter">Filter</button>
                                    <!-- <button class="btn btn-info" type="button" id="export"><i class="fas fa-file-export"></i> Export</button> -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="table" data-url="<?= base_url('reports/get_list') ?>">
                    <thead>
                        <tr>
                            <th class="default-sort" data-sort="desc">Tanggal</th>
                            <th>No Invoice</th>
                            <th>Nama User</th>
                            <th>Nama Seller</th>
                            <th>Subtotal</th>
                            <th>Ongkos Kirim</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>