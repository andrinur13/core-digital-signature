<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="card card-outline-primary">
   <div class="card-header">
      <h4 class="card-title">
      <button class="btn btn-default btn-round" data-provide="tooltip" data-placement="top" title="" data-original-title="Kembali" onclick="history.back();"><i class="fa fa-arrow-left"></i></button>
      Tambah Data Pengguna</h4>
   </div>

   <div class="card-body"> 
      <?php echo form_open($this->uri->uri_string(), 'id="form" role="form"'); ?>
      <div class="form-row">
         <div class="form-group col-4">
            <label> Parent Menu</label>
            <select name="parent" class="form-control select-parent" data-provide="selectpicker" title="Pilih..." data-width="85%">
               <?php foreach($parent as $item) {?>
               <option value="<?php echo $item['id'] ?>" <?php echo ($item['id'] == $menu['parent_id']) ? 'selected="selected"' : ''; ?> > <?php echo $item['name'] ?> </option>
               <?php } ?>
            </select>
            <a class="btn btn-pure btn-danger btn-xs" id="refresh-picker"><i class="ti-close"></i></a>
         </div>
      </div>
      <div class="form-row">
         <div class="form-group col-md-8">
            <label class="require" for="input-required">Name</label>
            <input type="text" class="form-control <?php echo form_error('menu') ? 'is-invalid' : ''; ?>"  name="menu" value="<?php echo $menu['name'] ?>">
            <div class="invalid-feedback"><?php echo validation_errors(); ?></div>
         </div>

         <div class="form-group col-md-6">
            <label class="require" for="input-required">Module</label>
            <input type="text" class="form-control" name="module" value="<?php echo $menu['module'] ?>">
            <?php echo validation_errors(); ?>
         </div>
         <div class="form-group col-md-8">
            <label class="">Description</label>
            <textarea name="description" class="form-control" rows="5"><?php echo $menu['desc'] ?></textarea>
          </div>
      </div>
      <div class="form-row">
         <div class="form-group col-md-2">
            <label>Icon Class</label>
            <input type="text" class="form-control" value="<?php echo $menu['icon'] ?>" name="icon">
         </div>
         <div class="form-group col-md-2">
            <label>Order No.</label>
            <input type="number" class="form-control" value="<?php echo $menu['order'] ?>" name="order">
         </div>
      </div>
      <div class="form-row">
         <div class="form-group col-md-2">
            <label>Is Show ?</label>
            <select name="is_show" class="form-control" data-provide="selectpicker">
               <option value="Ya" <?php echo $menu['is_show'] == 'Ya' ? 'selected="selected"' : ''; ?> >YA</option>
               <option value="Tidak" <?php echo $menu['is_show'] == 'Tidak' ? 'selected="selected"' : ''; ?> >TIDAK</option>
            </select>
         </div>          
      </div>
      <div class="form-row">
         <div class="form-group col-md-8 text-right">
            <hr class="hr-sm">
            <button class="btn btn-w-md btn-bold btn-primary" type="submit">SIMPAN</button>
         </div>
      </div>
      <!-- <div class="flexbox">
         <a class="btn btn-w-md btn-bold btn-primary" href="#">SIMPAN</a>
       </div> -->
   </form>
   </div>
</div>
<script type="text/javascript">
$(function() {
   $('#refresh-picker').on('click', function(e) { $('.select-parent').val('default').selectpicker('refresh');   });
});

</script>
