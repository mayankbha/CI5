<div class="white-area-content">
	<div class="db-header clearfix">
		<div class="page-header-title"><span class="glyphicon glyphicon-tasks"></span> <?php echo 'Templates'; //echo lang("ctn_820") ?></div>

		<div class="db-header-extra"><a href="<?php echo site_url("proposals/add") ?>" class="btn btn-primary btn-sm"><?php echo 'Create New Template'; //echo lang("ctn_821") ?></a></div>
	</div>

	<p><?php //echo lang("ctn_822") ?></p>

	<div class="panel panel-default">
		<div class="panel-body">
			<?php echo form_open(site_url("proposals/add"), array("class" => "form-horizontal")); ?>
				<div class="form-group">
					<label for="p-in" class="col-md-2 label-heading"><?php echo 'Template Title'; //echo lang("ctn_823") ?></label>

					<div class="col-md-10">
						<input type="text" class="form-control" name="title" />
					</div>
				</div>

				<div class="form-group">
					<label for="p-in" class="col-md-2 label-heading"><?php echo 'Content'; //echo lang("ctn_824") ?></label>

					<div class="col-md-10">
						<textarea name="description" id="content"></textarea>
					</div>
				</div>

				<input type="submit" class="btn btn-primary form-control" value="<?php echo 'Create Template'; //echo lang("ctn_837") ?>" />
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	CKEDITOR.replace('content', {height: '100'});

	$(document).ready(function() {
		
	});
</script>