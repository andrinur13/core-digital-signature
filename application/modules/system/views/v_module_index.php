<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if ($this->session->flashdata('msg_module')) {
   $msg = $this->session->flashdata('msg_module');
   ?>
<div class="alert alert-<?php echo $msg['type'];?> alert-dismissible fade show" role="alert">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
     <span aria-hidden="true">&times;</span>
   </button>
   <i class="fa fa-<?php echo $msg['status'] ? 'check' : 'warning'; ?>"></i> <?php echo $msg['text'];?>
</div>

<?php } ?>
<div class="card card-bordered">
   <div class="card-header">
      <h4 class="card-title"><strong>Data Menu Module</strong></h4>
      <div class="btn-toolbar">
         <a id="add-btn" class="btn btn-custom" href="<?php echo site_url($module . '/add_menu') ?>">
            Tambah Menu +
         </a>
        </div>
      </a>
   </div>

   <div class="card-body">
      <table class="table table-separated table-striped tab" id="datatables_ajax" >
         <thead class="bg-color-primary1">
            <tr>
               <th>Menu</th>
               <th>Sub Menu</th>
               <th>Module</th>
               <th>Method</th>
               <th>Deskripsi</th>
               <th>Menu Ditampilkan?</th>
               <th width="10%">Pilihan</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach($menu as $item) {?>
            <tr>
               <td colspan="2"><strong><?php echo $item['MenuName'] ?></strong></td>
               <td><?php echo $item['MenuModule'] ?></td>
               <td><?php echo $item['action_name'] ?></td>
               <td><?php echo $item['MenuDescription'] ?></td>
               <td class="text-center"><span class="badge badge-lg badge-<?php echo $item['MenuIsShow'] == 'Ya' ? 'info' : 'default'; ?> "><?php echo $item['MenuIsShow'] ?></span></td>
               <td class="table-actions">
                  <a href="<?php echo site_url($module.'/edit_menu/'.$item['MenuId']) ?>" class="btn btn-square btn-round btn-warning" data-provide="tooltip" title="Edit Menu"> <i class="ti-pencil"></i> </a>
               </td>
            </tr>
            <?php 
               if (isset($item['sub_menu'])) { 
                  foreach($item['sub_menu'] as $sm) {
					  
            ?> 
            <tr>
               <td style="border-right:none;"></td>
               <td style="border-left:none;"><?php echo $sm['MenuName'] ?></td>
               <td><?php echo $sm['MenuModule'] ?></td>
               <?php $method = explode(',', $sm['action_name']); ?>
               <td>
               <?php if (!is_null($sm['action_name'])) {foreach($method as $mtd) { echo '<span class="badge badge-lg badge-pill badge-primary" style="margin-bottom:5px;">'.$mtd.'</span> '; } }?>
               </td>
               <td><?php echo $sm['MenuDescription'] ?></td>
               <td class="text-center"><span class="badge badge-lg badge-<?php echo $sm['MenuIsShow'] == 'Ya' ? 'info' : 'default'; ?> "><?php echo $sm['MenuIsShow'] ?></span></td>
               <td class="table-actions">
                  <a href="<?php echo site_url($module.'/edit_menu/'.$sm['MenuId']) ?>" class="btn btn-square btn-round btn-warning" data-provide="tooltip" title="Edit Menu"> <i class="ti-pencil"></i> </a>
                  <a href="<?php echo site_url($module.'/method/'.$sm['MenuId']) ?>" class="btn btn-square btn-round btn-info" data-provide="tooltip" title="List Method Menu"> <i class="ti-layers"></i> </a>
               </td>   
            </tr>
            <?php } } } ?>
         </tbody>
      </table>
   </div>
</div>

<div class="modal modal-center" id="modal_dell" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"></h4>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $attributes = array('class' => 'form-horizontal','id' => 'form_dell');
          echo form_open_multipart('#', $attributes); ?>
        <input type="hidden" name="id_dell" id="id_dell">
      <div class="modal-body">
        <div class="container">
          Apa anda yakin ingin menghapus data ini?
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" onclick="del_action();" class="btn btn-bold btn-pure btn-danger">Hapus</button>
      </div>
    </div>
  </form>
  </div>
</div>
</div>