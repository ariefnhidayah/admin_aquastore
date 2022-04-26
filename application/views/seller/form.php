<!-- Begin Page Content -->
<div class="container-fluid">
	<?php $user = $this->session->userdata('user'); ?>

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800"><?= $deskripsi ?></h1>

	<div class="row">
		<div class="col-sm-12">
			<a href="<?= base_url('seller/') ?>" class="btn btn-primary">Kembali</a>
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
			<form action="" method="POST">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="form-group col-md-4">
								<label for="name">Nama Pemilik</label>
								<div>
									<input type="text" class="form-control <?= form_error('name') != '' ? 'is-invalid' : '' ?>" id="name" name="name"
										value="<?= set_value('name') != '' ? set_value('name') : ($data != '' ? $data->name : '') ?>">
									<?= form_error('name') ?>
								</div>
							</div>
							<div class="form-group col-md-4">
								<label for="store_name">Nama Toko</label>
								<div>
									<input type="text" class="form-control <?= form_error('store_name') != '' ? 'is-invalid' : '' ?>" id="store_name" name="store_name"
										value="<?= set_value('store_name') != '' ? set_value('store_name') : ($data != '' ? $data->store_name : '') ?>">
									<?= form_error('store_name') ?>
								</div>
							</div>
							<div class="form-group col-md-4">
								<label for="email">Email</label>
								<div>
									<input type="email" class="form-control <?= form_error('email') != '' ? 'is-invalid' : '' ?>" id="email" name="email"
										value="<?= set_value('email') != '' ? set_value('email') : ($data != '' ? $data->email : '') ?>">
									<?= form_error('email') ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="phone">No Handphone</label>
								<div>
									<input type="text" class="form-control <?= form_error('phone') != '' ? 'is-invalid' : '' ?>" id="phone" name="phone"
										value="<?= set_value('phone') != '' ? set_value('phone') : ($data != '' ? $data->phone : '') ?>">
									<?= form_error('phone') ?>
								</div>
							</div>
							<div class="form-group col-md-4">
								<label for="password">Password</label>
								<div>
									<input type="password" class="form-control <?= form_error('password') != '' ? 'is-invalid' : '' ?>" id="password" name="password"
										value="<?= set_value('phone') != '' ? set_value('phone') : ($data != '' ? $data->phone : '') ?>">
									<?= form_error('password') ?>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12 form-group">
								<label for="address">Alamat</label>
								<textarea name="address" id="address" cols="30" rows="5" class="form-control <?=form_error('address') != '' ? 'is-invalid' : ''?>"
									id="address"><?= set_value('address') != '' ? set_value('address') : ($data != '' ? $data->address : '') ?></textarea>
								<?= form_error('address') ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 form-group">
								<label for="province">Provinsi</label>
								<select name="province_id" id="province" class="form-control <?=form_error('province_id') ? 'is-invalid' : '' ?>">
									<option value="">Pilih Provinsi</option>
									<?php if ($provincies) foreach ($provincies->result() as $province) { ?>
									<option value="<?php echo $province->id ?>"
										<?php echo set_value('province_id') == $province->id ? 'selected' : (($data != '') ? $data->province_id == $province->id ? 'selected' : '' : '') ?>>
										<?php echo $province->name ?></option>
									<?php }  ?>
								</select>
								<?= form_error('province_id') ?>
							</div>
							<div class="col-md-4 form-group">
								<label for="city">Kota / Kabupaten</label>
								<select name="city_id" id="city" class="form-control <?=form_error('city_id') ? 'is-invalid' : '' ?>">
									<option value="">Pilih Kota / Kabupaten</option>
									<?php if ($cities) foreach ($cities->result() as $city) { ?>
									<option value="<?php echo $city->id ?>"
										<?php echo set_value('city_id') == $city->id ? 'selected' : (($data != '') ? $data->city_id == $city->id ? 'selected' : '' : '') ?>>
										<?php echo $city->type . ' ' . $city->name ?></option>
									<?php }  ?>
								</select>
								<?= form_error('city_id') ?>
							</div>
							<div class="col-md-4 form-group">
								<label for="district">Kecamatan</label>
								<select name="district_id" id="district" class="form-control <?=form_error('district_id') ? 'is-invalid' : '' ?>">
									<option value="">Pilih Kecamatan</option>
									<?php if ($districts) foreach ($districts->result() as $district) { ?>
									<option value="<?php echo $district->id ?>"
										<?php echo set_value('district_id') == $district->id ? 'selected' : (($data != '') ? $data->district_id == $district->id ? 'selected' : '' : '') ?>>
										<?php echo $district->name ?></option>
									<?php }  ?>
								</select>
								<?= form_error('district_id') ?>
							</div>
						</div>
						<hr />
						<div class="row">
							<div class="col-md-12 form-group">
								<label>Jasa Pengiriman</label>
								<div>
									<?php foreach ($shippings as $shipping) : ?>
									<div class="form-check form-check-inline">
										<input type="checkbox" class="form-check-input" id="checkbox-<?=$shipping?>"
											value="<?=$shipping?>" name="courier[]"
											<?php echo set_value('courier[]') != '' ? in_array($shipping, set_value('courier[]')) ? 'checked' : '' : '' ?> />
										<label for="checkbox-<?=$shipping?>"
											class="form-check-label"><?php echo strtoupper($shipping) ?></label>
									</div>
									<?php endforeach; ?>
								</div>
								<?= form_error('courier[]') ?>
							</div>
							<div class="form-group col-sm-4">
								<label>Bank</label>
								<input type="text" name="bank_name" id="bank_name"
									class="form-control <?= form_error('bank_name') != '' ? 'is-invalid' : '' ?>"
									value="<?= set_value('bank_name') ?>">
								<?= form_error('bank_name') ?>
							</div>
							<div class="form-group col-sm-4">
								<label>Nomor Akun</label>
								<input type="text" name="account_number" id="account_number"
									class="form-control <?= form_error('account_number') != '' ? 'is-invalid' : '' ?>"
									value="<?= set_value('account_number') ?>">
								<?= form_error('account_number') ?>
							</div>
							<div class="form-group col-sm-4">
								<label>Nama Akun</label>
								<input type="text" name="account_holder" id="account_holder"
									class="form-control <?= form_error('account_holder') != '' ? 'is-invalid' : '' ?>"
									value="<?= set_value('account_holder') ?>">
								<?= form_error('account_holder') ?>
							</div>
						</div>
						<hr />
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
