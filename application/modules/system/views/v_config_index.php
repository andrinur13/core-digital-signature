<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="card card-outline-primary">
    <div class="card-header">
		<h4 class="card-title"><strong>Management Pengaturan</strong></h4>
        <div class="btn-toolbar">
            
        </div>
	</div>
    
    <div class="card-body" id="tbl-container">
        <div class="row">
	  		<div class="col-md-3"></div>
	  		<div class="col-md-2"></div>
	  		<div class="col-md-2"></div>

			<div class="col-md-2">
				<!-- <select name="f_aksi" id="f_aksi" class="form-control bulk-action-selection" data-provide="selectpicker" data-live-search="true">
						<option value="">-Pilihan Aksi -</option>
						<option value="delete_all">Hapus</option>
					
				</select> -->
			</div>
			<div class="col-md-2">  
               <!-- <div class="form-group ">
                   <button type="submit" class="btn btn-label btn-bold btn-info table-group-action-submit" onclick="bulkAction()"> Submit <label><i class="ti-check"></i></label></button>
               </div> -->
            </div>
        </div>

        <table class="table table-separated table-striped tab" cellspacing="0" id="datatables_ajax">
            <thead class="bg-color-primary1">
				<tr role="row" class="heading">
					<th>Nama Pengaturan</th>
					<th>Jenis Pengaturan</th>
					<th>Nilai</th>
					<th>Aksi</th>
				</tr>
				</thead>
			</table>
    </div>

</div>


<!-- Javascript -->
<script type="text/javascript">
$(function() {

var ajaxParams = {};
var setAjaxParams = function(name, value) {
    ajaxParams[name] = value;
};

var dt = $('#datatables_ajax').DataTable({
  "processing": true,
  "serverSide": true,
  "ajax" : {
     "url" : "<?php echo site_url( $module . '/ajax/datatables');?>",
     "type" : "POST",
     "data" : function(d) {
            $.each(ajaxParams, function(key, value) {
            d[key] = value;
            });
    }
  },
  'drawCallback': function( settings ) {
     $('[data-provide="tooltip"]').tooltip();
  },
  //"dom": "<'row'<'col-md-4 col-sm-12'l<'table-group-actions pull-right'>>r><'table-scrollable't><'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'p>>",
  'language' : {
     'search': 'Cari',
     'searchPlaceholder':'Masukan Kata Kunci....',
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
  'order': [[ 1, 'desc' ]],
  'columnDefs': [
    //  {"visible": false, "targets":[1]},
     {"orderable": false, "searchable": false, "targets": [1,2,3]},
    //  {"className":"text-center", "targets": [0,1]}
  ]
});

dt.on( 'order.dt search.dt', function () {
    dt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        // cell.innerHTML = i+1;
    });
}).draw();

$('#datatables_ajax').on('click', '#delete', function(e) {
  e.preventDefault();
  var action = $(this).attr('href');

  app.modaler({
     html: 'Apakah Anda yakin ingin menghapus data ini?',
     title: 'Delete Method',
     cancelVisible: true,
     confirmText: 'YA',
     cancelText:'Tidak',
     cancelClass: 'btn btn-w-sm btn-secondary',
     confirmClass: 'btn btn-w-sm btn-danger',
     onConfirm: function() {
        $.get(action, function (result) {
           var status = (result.status == true) ? 'success' : 'danger';
           modalAlert({type:status, message:result.msg});
           dt.ajax.reload();
        }, "json");
     }
  });
});


$('#add-btn').on('click', function(e) {
    window.location.replace("<?php echo site_url($module . '/add');?>");
});

$('#frmFilter').on('submit', function(e) {
    e.preventDefault();
    var _this = $(this);
    $('input, select', _this).each(function(){
        setAjaxParams($(this).attr('name'), $(this).val());
    });

    dt.ajax.reload();
});


});




// Bulk Change

function bulkAction() {
    let action = $('#f_aksi').val();
    if (action === 'delete_all') {
        var selected_items = [];
        $('input[name^="data_id"]:checked').each(function() {
            selected_items.push($(this).val());
        });
        if (selected_items.length > 0) {
            $.ajax({
                url: '<?php echo site_url( $module . '/ajax/datatables');?>',
                type: 'POST',
                data: {data: selected_items,customActionType : 'group_action',customActionName : 'Delete'},
                dataType: 'json',
                success: function(result) {
                        $('#datatables_ajax').DataTable().ajax.reload();
                        Swal.fire({
                            title: "Informasi",
                            text: result.customActionMessage,
                            icon: result.customActionStatus
                        });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                            title: "Informasi",
                            text: result.customActionMessage,
                            icon: result.customActionStatus
                        });
                }
            });
                
        } else {
            Swal.fire({
                title: "Error",
                text: 'Data yang anda pilih tidak ada',
                icon: 'error'
            });
        }
    } else {
        Swal.fire({
                title: "Error",
                text: 'Aksi yang dipilih tidak ada',
                icon: 'error'
            });
    }
}


function selectAll() {
    "use strict";
    if ($('.select-all').is(":checked")) {
        $(".data-id").prop("checked", true);
    } else {
        $(".data-id").prop("checked", false);
    }
}
</script>

