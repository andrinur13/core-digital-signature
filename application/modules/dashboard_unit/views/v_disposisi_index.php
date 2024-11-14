<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style>
  .mb-4, .my-4 {
    margin-bottom: 0.5rem!important;
  }
</style>

<?php if ($this->session->flashdata('message_form')) {
      $msg = $this->session->flashdata('message_form');

    ?>

    <div class="callout callout-<?php echo $msg['type'];?>" role="alert">
      <button type="button" class="close" data-dismiss="callout" aria-label="Close">
        <span>×</span>
      </button>
      <h5><?php echo $msg['title'];?></h5>
      <p><?php echo $msg['message'];?></p>
    </div>

    <?php } ?>

<div class="card box">

	<div class="card-header"><h4 class="card-title">Disposisi Surat</h4></div>

    <div class="card-body" id="tbl-container">
 		<table class="table table-striped table-bordered" cellspacing="0" data-provide="datatables" id="datatables_ajax">
        	<thead>
          	<tr>
                <th>No</th>
                <th>Aksi</th>
                <th>Nomor Surat</th>
                <th>Tanggal Surat</th>
                <th>Tahun / Kategori Pokok Masalah</th>
                <th>Isi Surat</th> 
                <th>File Surat</th>
          	</tr>
        	</thead>
      </table>
	</div>

</div>

 
<script type="text/javascript">
$(function() { 
 
   var ajaxParams = {};
   var setAjaxParams = function(name, value) {
      ajaxParams[name] = value;
   };

    $dt = $('#datatables_ajax').DataTable({
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[20, 35, 50, -1], [20, 35, 50, "All"]],
        "pageLength": 20,
        "ajax" : {
            "url" : "<?php echo site_url($module . '/ajax/datatables'); ?>",
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
        'language' : {
            'search': 'Cari',
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
        'order': [[ 2, 'asc' ]],
        'columnDefs': [

            {"className": "text-center", "targets": [1]}, 
            {"orderable": false, "searchable": false, "targets": [2]}, 
        ]
    });

    $('#frmFilter').on('submit', function(e) {
      e.preventDefault();

      var _this = $(this);
      $('input, select', _this).each(function(){
         setAjaxParams($(this).attr('name'), $(this).val());
      });

      $dt.ajax.reload();
   });



    $('#datatables_ajax').on('click', '#editButton', function(e) {
        e.preventDefault();
        app.modaler({
            title: 'Detil Surat',
            size: 'lg',
            url: $(this).attr('data-url'),
            footerVisible: false
        });
    });


});

</script>
