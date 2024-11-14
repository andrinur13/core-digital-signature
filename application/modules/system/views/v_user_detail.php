<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="card card-outline-primary">
	<div class="card-header">
		<h4 class="card-title">
    <button class="btn btn-default btn-round" data-provide="tooltip" data-placement="top" title="" data-original-title="Kembali" onclick="history.back();"><i class="fa fa-arrow-left"></i></button>
    Detail Data Pengguna</h4>
	</div>

		<div class="card-body">
			<?php echo form_open_multipart($module);?>
            <div class="row">

               <!-- <div class="col-md-2">
                  <img src="<?php // echo base_url('upload/foto/user/'.$data[0]['UserFoto']);?>" width="100%">
               </div> -->

               <div class="col-md-4">
                  <hr class="d-md-none">

                  <div class="form-group">
                     <label class="" for="input-required">Username</label>
                     <p><?=$data[0]['UserName'];?></p>
                  </div>
                  <hr class="hr-sm">
                  <div class="form-group">
                     <label class="" for="input-required">Nama</label>
                     <p><?=$data[0]['UserRealName'];?></p>
                  </div>
                  <hr class="hr-sm">
                  <div class="form-group">
                     <label>Email</label>
                     <p><?=$data[0]['UserEmail'];?></p>
                  </div>
                  <hr class="hr-sm">
                  <div class="form-group">
                     <label class="" for="input-required">Group Pengguna</label>
                     <div class="custom-controls-stacked">
                        <?php foreach ($data_group as $grp) {?>
                        <label class="custom-control">
                        <span class="custom-control-description"><?=$grp->GroupName;?>
                        <?php if ($grp->UserGroupIsDefault=='Ya') {?>
                        <span class="badge badge-pill badge-info">Default</span>
                        <?php }?>
                        </span>
                        </label>
                        <?php } ?>
                     </div>
                  </div>
                  <hr class="hr-sm">
                  <div class="form-group">
                     <label>User isActive?</label>
                     <p><span class="badge badge-pill badge-success">Aktif</span></p>
                  </div>
               </div>
            </div>
      </div>
      </form>
   </div>
</div>
