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

			<div class="row">
				<div class="col-12">
					<div class="">
						<a class="btn btn-sm btn-round btn-custom float-right mb-3" href="javascript:void()" onclick="add()">+ Tambah</a>
					</div>
				</div>
			</div>

			<form class="form-type-round" id="frmFilter" action="POST">
				<div class="row no-gutters">
					<div class="col-12 col-md-2">
						<div class="form-group mt-2">
							<select class="form-control selectpicker" name="isbaca" id="isbaca" data-provide="selectpicker" data-live-search="false" data-title="Status Baca" data-header="Status Baca">
								<option value="all">Semua</option>
								<option value="1">Sudah Dibaca</option>
								<option value="0">Belum Dibaca</option>
							</select>
						</div>
					</div>

					<div class="col-12 col-md-2">
						<div class="form-group mt-2 ml-2">
							<select class="form-control selectpicker" name="sifat" id="sifat-filter" data-provide="selectpicker" data-live-search="false" data-title="Urgensi" data-header="Urgensi">
								<option value="">Semua</option>
								<?php foreach ($sifat as $val) { ?>
									<option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="col-12 col-md-3">
						<div class="form-group mt-2 ml-2">
							<select class="form-control selectpicker" name="asal" id="asal-filter" data-provide="selectpicker" data-live-search="true" data-title="Asal Surat" data-header="Asal Surat">
								<option value="">Semua</option>
								<?php foreach ($unit as $val) { ?>
									<option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="col-12 col-md-2">
						<div class="form-group mt-2 ml-2">
							<select class="form-control selectpicker" name="kategori" data-provide="selectpicker" data-title="Kategori surat" data-header="Kategori surat">
								<option value="">Semua</option>
								<option value="Internal">Internal</option>
								<option value="Eksternal">Eksternal</option>
							</select>
						</div>
					</div>

					<div class="col-12 col-md-3">
						<div class="input-daterange input-group mt-2 ml-2" id="datepicker">
							<input type="text" class="btn input-sm form-control" name="tanggal" value="<?= $tgl_awal ?>" />
							<!-- <span class="input-group-addon">to</span> -->
							<i class="fa fa-exchange pt-10"></i>
							<input type="text" class="btn input-sm form-control" name="tanggal_akhir" value="<?= $tgl_akhir ?>" />
						</div>
					</div>

				</div>
			</form>

			<table class="table table-separated table-striped mt-3" id="datatables_ajax" cellspacing="0">
				<thead class="bg-color-primary1">
					<tr>
						<th class="font-weight-bold">Tanggal</th>
						<th class="font-weight-bold">Sifat Urgensi</th>
						<th class="font-weight-bold">No. Surat</th>
						<th class="font-weight-bold">Asal Surat</th>
						<th class="font-weight-bold">Perihal</th>
						<th class="font-weight-bold">File</th>
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

<!-- modal delete -->
<div class="modal modal-center" id="modal_dels" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="">Hapus Surat Masuk</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<input type="hidden" id="id_deletes">
			<div class="modal-body">
				<div class="container" id="">
					Apakah anda ingin menghapus surat ini?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">Batal</button>
					<button type="button" onclick="del_surat();" class="btn btn-bold btn-pure btn-danger">Hapus</button>
				</div>
			</div>
			</form>
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

		$('.input-daterange').datepicker({
			clearBtn: true,
			autoclose: true,
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
					"targets": [1, 2, 3, 4, 5, 6]
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

		// $('#datatables_ajax tbody').on('dblclick', 'tr', function() {
		// 	var data = $dt.row(this).data();
		// 	alert('You clicked on ' + data[0] + '\'s row');
		// 	window.location.href = "<?= site_url($module . '/detail/') ?>";
		// });
	});

	var Modal = function(opt) {
		opt = opt || {};
		app.modaler({
			onHide: opt.hide,
			url: opt.url,
			footerVisible: opt.footer,
			confirmVisible: false,
			onConfirm: opt.callback,
			title: opt.title,
			headerVisible: true,
			size: opt.size,
			type: opt.type,
			backdrop: false,
		});
	}

	function add() {
		module = "<?= site_url($module . '/add?tipe=view') ?>";
		Modal({
			url: module,
			title: 'Tambah Surat Masuk',
			size: 'lg',
			callback: function(modal) {}
		})
	}

	function ubah(id) {
		module = "<?= site_url($module . '/update?tipe=view&id=') ?>" + id;
		Modal({
			url: module,
			title: 'Ubah Surat Masuk',
			size: 'lg',
			callback: function(modal) {}
		})
	}

	function balas_(id) {
		module = "<?= site_url($module . '/draft?tipe=view&id=') ?>" + id;
		Modal({
			url: module,
			title: 'Balas Surat',
			footer: true,
			size: 'lg',
			callback: function(modal) {}
		})
	}

	// balas dg draft
	var modalAdd = function(opt) {
		opt = opt || {};
		app.modaler({
			title: 'Tambah Konsep Surat Keluar',
			url: opt.url,
			footerVisible: false,
			size: "lg",
			bodyExtraClass: "form-type-round",
			onConfirm: opt.callback,
			backdrop: false,
		});
	}

	function balas(id) {
		var module = "<?php echo site_url('surat_keluar/Konsep/add/') ?>" + id;
		modalAdd({
			url: module,
			callback: function(modal) {}
		});
	}
	// balas dg draft

	function file(data, param = '') {

		if (param != '') {
			var modal = $(".modal.show").attr('id');
			$('#' + modal + '').modal('hide');
		}
		var file = "<?= base_url($path . '/'); ?>" + data;
		$('#embed-file').attr('data', file);
		$('#modal-file').modal('show');
	}

	function hapus(id) {
		$('#id_deletes').val(id);
		$('#modal_dels').modal('show');
	}

	function del_surat() {
		var id = $('#id_deletes').val();
		$.ajax({
			url: "<?= site_url($module . '/delete?id=') ?>" + id,
			dataType: 'JSON',
			type: 'POST',
			data: {
				id: id,
			},
			success: function(data) {
				if (data.error == 'null') {
					$('#datatables_ajax').DataTable().ajax.reload(false, null);
					$('#modal_dels').modal('hide');
					app.toast(data.text);
				} else {
					$('#modal_dels').modal('hide');
					app.toast(data.text);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('#modal_dels').modal('hide');
				app.toast('Gagal memproses! Silahkan hubungi administrator.');
			}
		});
	}
</script>