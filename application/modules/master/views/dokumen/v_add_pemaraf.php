<?php defined('BASEPATH') OR exit('No direct script access alloweds');?>
<div class="card card-bordered">
    <div class="card-header border-info">
		<h4 class="card-title"><strong><?= $template['title']; ?></strong></h4>
        <div class="card-header-actions" id="actionButton">
            <div class="btn-toolbar">
           
            </div>
        </div>
        
	</div>
    
    <div class="card-body">
        
        <?php echo form_open_multipart($moduleAdd,  'class="form-horizontal" '); ?>
        <input type="hidden" name="dokumen_id" value="<?= $dokumen_id ?>">
            <ul class="nav nav-process nav-process-circle">
                <li class="nav-item complete">
                    <span class="nav-title">Naskah Dokumen</span>
                    <a class="nav-link" data-toggle="tab" href="#naskah"></a>
                </li>

                <li class="nav-item processing">
                    <span class="nav-title">Pemaraf</span>
                    <a class="nav-link active" data-toggle="tab" href="#pemaraf"></a>
                </li>

                <li class="nav-item">
                    <span class="nav-title">Penandatangan</span>
                    <a class="nav-link" data-toggle="tab" href="#wizard-basic-3"></a>
                </li>
            </ul>


            <div class="tab-content">
                <div class="tab-pane fade active show" id="pemaraf" data-provide="validation">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <button id="cari-btn" class="btn btn-label btn-bold btn-primary" type="button">
                                Pejabat Pemaraf
                                <label><i class="ti-search"></i></label>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <table class="table table-striped table-bordered" id="tablePejabat" cellspacing="0" data-provide="datatables">
                    <thead>
                        <tr>
                            <th>Kode Pejabat</th>
                            <th>Nama Pejabat</th>
                            <th>Jabatan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="tempPejabatList" style="display: none;">
                            <td class="fw-400 kode_pejabat"></td>
                            <td class="fw-400 nama_pejabat"></td>
                            <td class="text-center jabatan_pejabat"></td>
                            <td>
                                <input type="hidden" id="id-pejabat" name="pejabat_id" >                                    
                                <a id="delPejabat" class="btn btn-square btn-danger text-white table-action"  data-provide="tooltip" title="Hapus Pejabat"><i class="ti-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <div class="flexbox">
                <a class="btn btn-secondary" href="<?php echo site_url($createStepOne); ?>">Kembali</a>
                <button type="submit" class="btn btn-success" >Next</button>
            </div>
        <?php form_close(); ?>
    </div>

</div>
<script>
    $(function() {

    $('#cari-btn').on('click', function(e) {
        e.preventDefault();
        var act_url = '<?php echo site_url($module.'/ajax/modal_pejabat'); ?>';
        app.modaler({
            title: 'Daftar Pejabat',
            url: act_url,
            size:'lg',
            footerVisible: false
        });
    });

    $('#tablePejabat').on('click', '#delPejabat', function(e) {
        //console.log('tes');
        e.preventDefault();
        let $nextTR = $(this).parents('tr').nextAll(); console.log( $(this).parents('tr'));
        $.each($nextTR, function(idx, row) {
            var number = $(row).find('[scope="row"]').text();
            $(row).find('[scope="row"]').text(number - 1);
        });
        $(this).parents('#pejabat').remove();
    });

    
});
</script>
