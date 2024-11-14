<form class="" method="POST" action="<?php echo site_url($module . '/add_method/'.$menu_id) ?>">
   <div class="card-body"> 
      <div class="form-group">
         <label class="require"> Name :</label>
         <input type="text" name="name" class="form-control">
      </div> 
      <div class="form-group">
         <label class="require"> Method :</label>
         <input type="text" name="method" id="method" class="form-control" autocomplete="off">
         <small class="form-text">segmen: <b><?php echo $menu['module']?>/<span id="segmen"></span></small>
      </div>
   </div>

   <footer class="card-footer text-right">
      <button class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
      <button class="btn btn-primary" type="submit">SIMPAN</button>
   </footer>
</form>
<script type="text/javascript">
$(function() {
   $('#method').on('keyup', function() {
      $('#segmen').text($(this).val());
   });
});
</script>