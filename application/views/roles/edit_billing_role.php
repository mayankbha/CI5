<div class="white-area-content">

    <div class="db-header clearfix">
        <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_890") ?></div>
        <div class="db-header-extra">
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <?php echo form_open(site_url("team/edit_billing_role_pro/" . $role->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_320") ?></label>
                <div class="col-md-8 ui-front">
                    <input type="text" class="form-control" name="role_name" value="<?php echo $role->name ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1413") ?></label>
                <div class="col-md-8 ui-front">
                    <input type="text" class="form-control" name="role_price" value="<?php echo $role->price ?>">
                </div>
            </div>

            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_909") ?>">
            <?php echo form_close() ?>
        </div>

    </div>
</div>