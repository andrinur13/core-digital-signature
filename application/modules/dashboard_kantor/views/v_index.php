<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!--
<div class="page-header">
	<h1>
		Beranda
	</h1>
</div> 

 /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<div class="row">
		<?php
			if(!is_null($message)){
		?>
			<div class="alert alert-<?php echo $message['status'];?>">
				<button type="button" class="close" data-dismiss="alert">
					<i class="ace-icon fa fa-times"></i>
				</button>
				<strong>
					<i class="ace-icon fa fa-check"></i>
					Informasi!!
				</strong>
				<?php echo $message['text'];?>
				<br />
			</div>
		<?php
			}
		?>
		
		</div><!-- /.row -->
	</div>
</div>
