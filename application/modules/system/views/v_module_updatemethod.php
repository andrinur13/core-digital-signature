<form class="" method="POST" action="<?php echo site_url($module . '/update_method/'.$id) ?>">
   <div class="card-body"> 
      <div class="form-group">
         <label> Name :</label>
         <input type="text" name="name" class="form-control" value="<?php echo $method['name']; ?>">
      </div> 
      <div class="form-group">
         <label> Method :</label>
         <input type="text" name="method" id="method" class="form-control" value="<?php echo $method['method']; ?>">
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