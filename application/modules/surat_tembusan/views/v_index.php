<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
	tr {
		cursor: default !important;
	}
</style>

<!-- table -->
<div class="col-lg-12">
	<div class="card shadow-2">
		<div class="card-body">

			<div class="mb-2 d-flex justify-content-between">
				<form class="form-type-round" id="frmFilter" action="POST">
					<div class="btn-toolbar d-flex flex-wrap">
						<div class="form-group mt-2 d-none">
							<select class="form-control selectpicker" name="isbaca" id="isbaca" data-provide="selectpicker" data-live-search="false" data-title="Status Baca" data-header="Status Baca">
								<option value="all">Semua</option>
								<option value="1">Sudah Dibaca</option>
								<option value="0">Belum Dibaca</option>
							</select>
						</div>

						<div class="form-group mt-2 ml-2">
							<select class="form-control selectpicker" name="sifat" id="sifat-filter" data-provide="selectpicker" data-live-search="false" data-title="Urgensi" data-header="Urgensi">
								<option value="">Semua</option>
								<?php foreach ($sifat as $val) { ?>
									<option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group mt-2 ml-2">
							<select class="form-control selectpicker" name="asal" id="asal-filter" data-provide="selectpicker" data-live-search="true" data-title="Asal Surat" data-header="Asal Surat">
								<option value="">Semua</option>
								<?php foreach ($unit as $val) { ?>
									<option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group mt-2 ml-2">
							<select class="form-control selectpicker" name="kategori" data-provide="selectpicker" data-title="Kategori surat" data-header="Kategori surat">
								<option value="">Semua</option>
								<option value="Internal">Internal</option>
								<option value="Eksternal">Eksternal</option>
							</select>
						</div>

						<div class="form-group mt-2 ml-2">
							<input type="text" class="btn form-control w-50" name="tanggal" id="filter-tanggal" placeholder="dd-mm-yyyy">
						</div>

						<div class="form-group mt-2 ml-2 d-none">
							<select class="form-control selectpicker" name="tindakan" id="tindakan" data-provide="selectpicker" data-live-search="true" data-title="Status Tindakan" data-header="Status tindakan">
								<option value="">Semua</option>

							</select>
						</div>

					</div>
				</form>

				<div class="ml-2">

				</div>
			</div>

			<table class="table table-separated table-striped" id="datatables_ajax" cellspacing="0">
				<thead class="bg-color-primary1">
					<tr>
						<th class="font-weight-bold">Tanggal</th>
						<th class="font-weight-bold">Sifat</th>
						<th class="font-weight-bold">No. Surat</th>
						<th class="font-weight-bold">Asal Surat</th>
						<th class="font-weight-bold">Perihal</th>
						<th class="font-weight-bold">Aksi</th>
					</tr>
				</thead>
			</table>

		</div>
	</div>
</div>

<!-- modal file -->
<div class="modal" id="modal-file" tabindex="-1" style="min-height: 100%;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-body">
			<div class="modal-content">
				<object id="embed-file" data="" type="application/pdf" style="min-height:100vh;width:100%"></object>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('form input,select').on('change', function() {
			$(this).closest('form').submit();
		});

		$('#filter-tanggal').datepicker({
			autoclose: true,
			clearBtn: true,
			todayHighlight: true,
			format: 'dd-mm-yyyy',
			orientation: "bottom auto"
		});
	});

	$(function() {
		var ajaxParams = {};
		var setAjaxParams = function(name, value) {
			ajaxParams[name] = value;
		};
		$dt = $('#datatables_ajax').DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			// "lengthMenu": [
			//    [20, 35, 50, -1],
			//    [20, 35, 50, "All"]
			// ],
			// "pageLength": 20,

			"ajax": {
				"url": "<?php echo site_url($module . '/datatables'); ?>",
				"type": "POST",
				"data": function(d) {
					$.each(ajaxParams, function(key, value) {
						d[key] = value;
					});
				}
			},
			'drawCallback': function(settings) {
				$('[data-provide="tooltip"]').tooltip();
			},
			'language': {
				'search': 'Cari',
				'searchPlaceholder': 'Nomor/asal/hal',
				'lengthMenu': "Tampil _MENU_",
				'info': "_START_ - _END_ dari _TOTAL_",
				"paginate": {
					"previous": "Prev",
					"next": "Next",
					"last": "Last",
					"first": "First",
					"page": "Page",
					"pageOf": "of"
				}
			},
			'order': [
				[0, 'DESC']
			],
			"columnDefs": [{
					"orderable": false,
					"targets": [1, 2, 3, 4, 5]
				},
				{
					"className": "text-center",
					"targets": []
				},
				{
					"className": "nowrap",
					"targets": [0, 1, 2]
				},
			]
		});

		$('#frmFilter').on('submit', function(e) {
			e.preventDefault();

			var _this = $(this);
			$('input, select', _this).each(function() {
				setAjaxParams($(this).attr('name'), $(this).val());
			});

			$dt.ajax.reload();
		});
	});

	var Modal = function(opt) {
		opt = opt || {};
		app.modaler({
			onHide: opt.hide,
			url: opt.url,
			footerVisible: false,
			onConfirm: opt.callback,
			title: opt.title,
			headerVisible: true,
			size: opt.size,
			type: opt.type,
			backdrop: false
		});
	}

	function detail(id) {
		module = "<?= site_url($module . '/detail?id=') ?>" + id;
		Modal({
			url: module,
			title: 'Detail Disposisi Masuk',
			size: 'lg',
			callback: function(modal) {}
		})
	}

	function file(data, param = '') {

		if (param != '') {
			var modal = $(".modal.show").attr('id');
			$('#' + modal + '').modal('hide');
		}
		var file = "<?= base_url($path . '/'); ?>" + data;
		$('#embed-file').attr('data', file);
		$('#modal-file').modal('show');
	}
</script>