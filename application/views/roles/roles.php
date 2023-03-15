<div class="white-area-content">

    <div class="db-header clearfix">
        <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_890") ?></div>
        <div class="db-header-extra"> <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_914") ?></button>
        </div>
    </div>

    <p><?php echo lang("ctn_915") ?></p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tr class="table-header"><td><?php echo lang("ctn_320") ?></td><td><?php echo lang("ctn_1413") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
            <?php foreach($roles->result() as $r) : ?>
                <tr>
                    <td><?php echo $r->name ?></td>
                    <td><?php echo $r->price ?></td>
                    <td><a href="<?php echo site_url("team/edit_billing_role/" . $r->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("team/delete_billing_role/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" onclick="return confirm('<?php echo lang("ctn_508") ?>')" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>


<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_914") ?></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart(site_url("team/add_billing_role"), array("class" => "form-horizontal")) ?>
                <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_320") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="role_name"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1413") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="role_price"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
                <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_914") ?>">
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>