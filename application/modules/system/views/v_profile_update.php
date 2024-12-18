<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if ($this->session->flashdata('msg_register')) {
	$msg = $this->session->flashdata('msg_register');
?>
<div class="callout callout-<?php echo $msg['type'];?>" role="alert">
  <button type="button" class="close" data-dismiss="callout" aria-label="Close">
    <span>×</span>
  </button>
  <h5><?php echo $msg['title'];?></h5>
  <p><?php echo $msg['text'];?></p>
</div>

<?php } ?>

<div class="card box card-bordered">
	<div class="card-header">
		<h4 class="card-title">
		<button class="btn btn-default btn-round" data-provide="tooltip" data-placement="top" title="" data-original-title="Kembali" onclick="history.back();"><i class="fa fa-arrow-left"></i></button>
		Ubah Data Pengguna</h4>
	</div>

	<div class="card-body">
		<?php echo form_open_multipart($module.'/'.encode($data_user[0]['UserId']));?>
         <div class="row">
            <div class="col-md-8">
               <hr class="d-md-none">
               <div class="form-group">
                  <label class="" for="input-required">Username :</label>
                   <input type="text" class="form-control" disabled value=" <?=$data_user[0]['UserName'];?>">
               </div>
                <div class="form-group">
                  <label class="" for="input-required">Nama Pengguna</label>
                  <input type="text" class="form-control" disabled value="<?=$data_user[0]['UserRealName'];?>">
               </div>
               
               <div class="form-group">
                  <label class="" for="input-required">Group Pengguna</label>
                  <input type="text" class="form-control" disabled value="<?=$data_user[0]['NamaGroup'];?>">
               </div>
               <div class="form-group">
                  <label>Password</label>
                  <p><small>Kosongkan jika tidak ingin memperbarui Password.</small></p>
                  <input type="password" class="form-control" value="" name="password_input" placeholder="Atur kata sandi disini">
               </div>
               <div class="form-group">
                  <label>Re-Password</label>
                  <input type="password" class="form-control" value="" name="password_input_re" placeholder="Ulangi kata sandi.">
               </div>
              
               
               <div class="form-group">
                  <div class="form-group text-right">
                     <button class="btn btn-w-md btn-bold btn-round btn-primary" type="submit">UBAH</button>
                  </div>
               </div>
            </div>
         </div>
         </form>
   </div>
</div>