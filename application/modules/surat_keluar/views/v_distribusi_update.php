<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="form-type-round" id="modalDistribusi">
   <form class="form-horizontal" id="formDistribusi" role="form" action="">
      <div class="card card-bordered card-body">
         <div id="newinput"></div>
         <button id="rowAdder" type="button" class="btn btn-xs btn-cyan" row-no="0">
            <i class="fa fa-plus"></i> Tambah Penerima
         </button>
      </div>

      <div class="card-footer text-right">
         <input type="hidden" name="action" value="submit">
         <button class="btn btn-label btn-bold btn-success" data-id="<?php echo $surat_id; ?>" data-perform="confirm" type="submit" id="btn-simpan-distribusi">Simpan <label><i class="ti-save"></i></label></button>
      </div>

   </form>
</div>

<script type="text/javascript">
   $(function() {

      $("#rowAdder").click(function() {
         newRowAdd = `
         <div id="row">
            <div class="row">
               <div class="form-group col-lg-12">
                  <label class="text-dark" for="penerima">Nama Penerima </label>
                  <input name="penerima[]" class="btn form-control" type="text" placeholder="Nama Penerima" autocomplete="off">
                  <span class="invalid-feedback error_penerima"></span>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-lg-6">
                  <label class="text-dark" for="email">Email</label>
                  <input name="email[]" class="btn form-control" type="text" placeholder="Email" autocomplete="off">
                  <i><span><small>cth. email@uad.ac.id</small></span></i>
               </div>
               <div class="form-group col-lg-6">
                  <label class="text-dark" for="no_wa">Nomor WhatsApp</label>
                  <div class="input-group">
                     <input name="no_wa[]" class="btn form-control digits" type="text" placeholder="Nomor WA" autocomplete="off">&nbsp;<button class="btn btn-danger" data-no="" id="DeleteRow" type="button"><i class="fa fa-trash"></i></button>
                  </div>
                  <i><span><small>cth. 6281234567890</small></span></i>
               </div>
            </div>
         </div>
      `;

         $('#newinput').append(newRowAdd);
      });
      $("body").on("click", "#DeleteRow", function() {
         $(this).parents("#row").remove();
      })
      $('body').on('keypress', '.digits', function(e) {
         // $(".digits").keypress(function(e) {
         //if the letter is not digit then display error and don't type anything
         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            Swal.fire({
               title: "Peringatan",
               text: "Inputan hanya angka.",
               icon: "warning"
            });

            return false;
         }
      });

      $('#btn-simpan-distribusi').on('click', function(e) {
         e.preventDefault(); // avoid to execute the actual submit of the form.
         var modal = $('.modal.fade.show').attr('id');
         var actionUrl = "<?php echo site_url($module . 'distribusi/') . $surat_id; ?>";
         $.ajax({
            url: actionUrl,
            type: 'POST',
            data: $('#formDistribusi').serialize(),
            dataType: 'json',
            success: function(result) {
               if (result.error == 'null') {
                  $('#datatables_ajax').DataTable().ajax.reload(null, false);
                  $('#' + modal).modal('hide');
                  Swal.fire({
                     title: "Informasi",
                     text: result.text,
                     icon: result.type
                  });
               } else {
                  $.each(result.error, function(i, log) {
                     if (log != '') {
                        $('[name="' + i + '"]').addClass('is-invalid');
                     } else {
                        $('[name="' + i + '"]').removeClass('is-invalid');
                     }
                     $('.error_' + i).text(log);
                  });

                  Swal.fire({
                     title: "Informasi",
                     text: "Periksa Semua Form Inputan",
                     icon: "warning"
                  });

               }
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus + errorThrown);
            }
         });

      });

   });
</script>