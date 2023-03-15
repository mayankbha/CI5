<div id="blackBar">
    <div class="white-area-content">
        <div class="db-header clearfix">
            <div class="page-header-title">
                <span class="glyphicon glyphicon-send">
                </span> Payment Details
            </div>
            <span class="pull-right" style="font-size: 24px;"><?php if(isset($proposals)) echo $proposals->title; ?></span>
        </div>
    <!--=== Page Content ===-->
        <div class="row">
            <!--=== Horizontal Forms ===-->
            <div class="col-md-12">
                <?php echo form_open_multipart(site_url("proposals/mark_as_paidServices"), array("class" => "form-horizontal")) ?>
                <!--<form class="form-horizontal"  method ="post" action="<?php //base_url("proposals/mark_as_paidServices"); ?>">-->
                    <div class="form-group">
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Mark as Paid</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="hidden" name="user_to_proposal_id" value="<?php if(isset($proposals)) echo $proposals->user_to_proposal_id; ?>">
                                   <input type="checkbox" name="mark_as_paid" class="checkbox" value="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label pull-left"></label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-8"><textarea rows="3" cols="5" name="payment_comment" class="auto form-control"></textarea></div>
                            </div>
                        </div>
                    </div>
                    <div class="services" style="display:none;">
                        <div class="form-group">
                            <label class="col-md-3 control-label pull-left">Set Up Services Initiate Date</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-10"></div>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($praposal_services) && !empty($praposal_services)){
                              foreach($praposal_services as $services){ ?>
                            <div class="form-group">
                                <input type="hidden" name="proposal_service_id[]" value="<?php echo $services['proposal_service_id']; ?>" />
                                <label class="col-md-3 control-label pull-left"><?php echo $services['name']; ?></label>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="col-md-6"><input class="form-control datepicker" name="start_date[]"  placeholder="Choose Start Date" type="text"></div>
                                    </div>
                                </div>
                            </div>
                        <?php } } ?>
                        <div class="form-group">
                        <div class="form-group">
                            <label class="col-md-3 control-label pull-left"></label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-8"><input type="submit" value="Save and Proceed" class="btn btn-primary pull-right" /></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--</form>-->
                <?php echo form_close() ?>
            </div>
            <!-- /Horizontal Forms -->
        </div>
      </div>
     </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mark_as_paid" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirm</h4>
            </div>

            <div class="modal-body">
                <p>You need tomark as paid initiate setup services.</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success yes" data-dismiss="modal">Yes</button>

                <button type="button" class="btn btn-default no" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            minDate: 0,
        });

        $(".checkbox").change(function() {
            if(this.checked) {
                $('#mark_as_paid').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                .on('click', '.yes', function(e) { 
                    $('#mark_as_paid').modal("hide");
                    $('.services').show();
                })
                .on('click', '.no', function(e) { 
                    $(".checkbox").attr('checked', false);
                });
               
            }else{
                 $('.services').hide();
            }
        });
    });
</script>