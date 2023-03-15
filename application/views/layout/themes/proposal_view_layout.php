<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php if(isset($page_title)) : ?><?php echo $page_title ?> - <?php endif; ?><?php echo $this->settings->info->site_name ?></title>

		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>scripts/libraries/ckeditor/plugins/bootstrap-3.3.6/css/bootstrap.min.css" />

		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>scripts/libraries/ckeditor/css/style.css" />

		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" />

		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>scripts/libraries/ckeditor/plugins/font-awesome-4.5.0/css/font-awesome.min.css" />

		<!-- Start of KEditor styles -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>scripts/libraries/ckeditor/css/keditor-1.1.5.min.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>scripts/libraries/ckeditor/css/keditor-components-1.1.5.min.css" />
		<!-- End of KEditor styles -->

		<!-- SCRIPTS -->
		<script type="text/javascript">
			var global_base_url = "<?php echo site_url('/') ?>";
			var global_hash = "<?php echo $this->security->get_csrf_hash() ?>";
		</script>

		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/libraries/ckeditor/plugins/jquery-1.11.3/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/libraries/ckeditor/plugins/bootstrap-3.3.6/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="<?php echo base_url(); ?>scripts/libraries/datetimepicker/jquery.datetimepicker.css" />
        <script src="<?php echo base_url(); ?>scripts/libraries/datetimepicker/jquery.datetimepicker.full.min.js"></script>

		<script type="text/javascript">
			var bsTooltip = $.fn.tooltip;
			var bsButton = $.fn.button;
		</script>

		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/libraries/ckeditor/plugins/jquery-ui-1.11.4/jquery-ui.min.js"></script>

		<script type="text/javascript">
			$.widget.bridge('uibutton', $.ui.button);
			$.widget.bridge('uitooltip', $.ui.tooltip);
			$.fn.tooltip = bsTooltip;
			$.fn.button = bsButton;
		</script>

		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/libraries/ckeditor/plugins/jquery.nicescroll-3.6.6/jquery.nicescroll.min.js"></script>

		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/libraries/ckeditor/plugins/ckeditor-4.5.6/ckeditor.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/libraries/ckeditor/plugins/ckeditor-4.5.6/adapters/jquery.js"></script>

		<!-- Start of KEditor scripts -->
		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/libraries/ckeditor/js/keditor-1.1.5.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/libraries/ckeditor/js/keditor-components-1.1.5.min.js"></script>
		<!-- End of KEditor scripts -->

        <!--<script type="text/javascript" src="<?php echo base_url(); ?>scripts/multiple-emails.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>styles/multiple-emails.css" />-->

		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/bootstrap-notify.min.js"></script>

        <style>
			#ui-datepicker-div {z-index: 1111 !important;}

            .inline-content {
                border: none;
                margin: 0px auto;
            }
		</style>
	</head>

	<body>
		<?php echo $content; ?>
	</body>

    <?php if($proposal_type == 2) { ?>
        <br></br>

        <div class="navbar navbar-default navbar-fixed-bottom" style="z-index: 1111 !important;">
            <div class="container">
                <span class="navbar-text" style="float: right; margin-bottom: 10px;">
                    <button type="button" class="btn btn-default" onClick="self.close();">Close</button>

                    &nbsp;&nbsp;&nbsp;&nbsp;

                    <button type="button" class="btn btn-success" onClick="show_proposal_popup();">Send</button>
                </span>
            </div>
        </div>

        <!-- Send Proposal Modal -->
        <div class="modal fade send_proposal_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>

                         <h4 class="modal-title" id="myModalLabel">Send Proposal</h4>
                    </div>

                    <div class="modal-body">
                        <form class="form-horizontal" role="form" name="send_proposal_form" id="send_proposal_form" action="">
                            <input type="hidden" class="form-control" id="price" name="price" />

                            <div class="form-group">
                                <label class="col-sm-12" for="inputEmail3">
                                    Receipient Email Address
                                </label>

                                <div class="col-sm-10">
                                    <!--<input type="text" class="form-control" name="emails" id="emails" data-role="tagsinput" value="" />-->

                                    <input type="text" class="form-control" id="emails" name="emails" placeholder="Email" />

                                    <small id="email_error" class="text-danger"></small> 
                                </div>
                            </div>

                            <!--<div class="form-group">
                                <label class="col-sm-4" for="inputEmail3">Title</label>

                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="title" id="proposal_title" placeholder="Enter Proposal Title" />
                                </div>
                            </div>-->

                            <div class="form-group">
                                <label class="col-sm-12" for="inputEmail3">
                                    Discount and Validity
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4" for="inputEmail3">Offering Discount</label>

                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="discount" id="discount" placeholder="Discount" placeholder="Percent" aria-describedby="basic-addon1" />

                                        <span class="input-group-addon" id="basic-addon1">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4" for="inputPassword3">Proposal Valid Upto</label>

                                <div class="col-sm-6">
                                    <input type="text" class="form-control datetimepicker1" name="proposal_validity" id="proposal_validity" placeholder="Proposal valid upto" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="send_proposal_btn" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Please wait..." onclick="send_proposal(<?php echo $proposal_id; ?>, 0, this.id);">Send</button>

                        <!--<button type="butto" id="send_proposal_edit_btn" class="btn btn-warning" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Please wait..." onclick="send_proposal(<?php echo $proposal_id; ?>, 1, this.id);">Send as Editable</button>-->
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $(".update_service_link_common").each(function() {
                $(this).remove();
            });
        });

        function show_proposal_popup() {
			//alert('Hi');

			$('.send_proposal_modal').modal('show');
		}

        function send_proposal(proposal_id, proposal_type, btn_id) {
			var title = $('#proposal_title_text').val();
			var emails = $('#emails').val();
			var discount = $('#discount').val();
			var proposal_validity = $('#proposal_validity').val();

            var price = $('#price').val();

            if(emails == '') {
                $('#email_error').html('Please enter email address');
                return false;
            } else if(!isValidEmailAddress(emails)) {
                $('#email_error').html('Invalid email address');
                return false;
            } else {
                $('#email_error').html('');
            }

			$('#'+btn_id).button('loading');

			$.ajax({
				type: "POST",
				url: global_base_url+"proposals/send_proposal",
				data: {'proposal_id' : proposal_id, 'proposal_type' : proposal_type, 'emails' : emails, 'discount' : discount, 'proposal_validity' : proposal_validity, 'price' : price, 'title' : title},
				cache: false,
				success: function(res) {
					//alert('success :: '+res);

					if(res != '' && res == 0) {
						//alert('Notify');

						$('#'+btn_id).button('reset');

						$('#emails').val('');
						$('#proposal_title').val('');
						$('#discount').val('');
						$('#proposal_validity').val('');

						$('.send_proposal_modal').modal('hide');

						$.notify('<strong>Success!</strong> Proposal has been sent successfully.', {
							allow_dismiss: false,
							delay: 5000,
							/*placement: {
								from: 'bottom',
								align: 'left'
							}*/
							offset: {
								x: 500,
								y: 220
							},
						});
					} else {
                        alert('Sorry! some error occured. Please try again later.');
                    }
				},
				error: function() {
					console.log('error');
				}
			});
		}

        function isValidEmailAddress(emailAddress) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

            return pattern.test(emailAddress);
        }

        function acceptProposal(proposal_id) { 
            updateUserProposal(proposal_id, 6, 'Accepted');
        }

        function declineProposal(proposal_id) { 
           updateUserProposal(proposal_id, 7, 'Declined');
        }

        function updateUserProposal(proposal_id, status, msg) {    
            if(status == 6) {
                var btnclass    =   "btn-primary";
            } else {
                 var btnclass    =   "btn-warning";
            }

            $.ajax({
                type: "POST",
                url: global_base_url+"proposals/updateUserProposal",
                data: {'proposal_id' : proposal_id, 'status' : status ,'updateUserProposal': true },
                cache: false,
                beforeSend: function() {
                    $('.'+btnclass).button('loading');
                },
                success: function(res) {
                    $('.'+btnclass).button('reset');

                    if(res == 0) {
                        $.notify('<strong>Success!</strong> Proposal has been '+msg+'.', {
                            allow_dismiss: false,
                            delay: 5000,
                            placement: {
                                from: 'bottom',
                                align: 'left'
                            },
                            offset: {
                                x: 500,
                                y: 220
                            },
                        });

                        setTimeout(function() {
                            window.location.href = global_base_url+"proposals/index/6";
                        }, 5000);
                    } else {
                        $.notify('<strong>Error!</strong>Getting Error.', {
                            allow_dismiss: false,
                            delay: 5000,
                            placement: {
                                from: 'bottom',
                                align: 'left'
                            },
                            offset: {
                                x: 500,
                                y: 220
                            },
                        });
                    }
                },
                error: function() {
                    console.log('error');
                }
            });
        }

        $(function() {
            document.body.innerHTML = $("body").html().replace(/[[]/g, '');
            document.body.innerHTML = $("body").html().replace(/]]/g, '');

            $('.datetimepicker1').datetimepicker({
                format : 'Y/m/d',
                minDate: 0,
                timepicker: false
            });
		});
    </script>
</html>