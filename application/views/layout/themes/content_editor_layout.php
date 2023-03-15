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

		<script>
			$(document).ready(function() {
				$('#proposal_title_text').hide();

				$('.edit-on-click').click(function() {
					$(this).hide();

					var label_text = $('#proposal_title_label').text();

					$('#proposal_title_text').val(label_text);

					$('#proposal_title_label').hide();
					$('#proposal_title_text').show();
				});

				$('#proposal_title_text').keyup(function(e) {
					if((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
						var p_text = $('#proposal_title_text').val();

						$('#proposal_title_label').text(p_text);

						$('#proposal_title_text').hide();
						$('#proposal_title_label').show();

						$('.edit-on-click').show();

                        getContent(<?php echo $proposal_id; ?>, 1);
					}
				});

				$('#proposal_title_text').focusout(function() {
					var p_text = $('#proposal_title_text').val();

					$('#proposal_title_label').text(p_text);

					$('#proposal_title_text').hide();
					$('#proposal_title_label').show();

					$('.edit-on-click').show();

                    getContent(<?php echo $proposal_id; ?>, 1);
				});
			});
		</script>

		<style>
			#ui-datepicker-div {z-index: 1111 !important;}

            .service_section {
                border: 0px solid;
                font-size: 20px;
                font-weight: bold;
                text-align: center;
                height: 140px;
                margin: 20px 0px 0px 0px;
                width: 100%;
                text-decoration: underline;
            }
            
            .service_section_span {
                width: 100%;
                color: #000;
            }
		</style>
	</head>

	<body>
		<div class="container" style="margin: 0px;">
			<div class="row">
				<div class="col-lg-12">
					<div class="inline-content">
						<div class="form-group row">
							<div class="col-xs-8" style="padding: 6px 0px 0px 54px;">
								<label id="proposal_title_label" style="font-size: 20px;"><?php echo $template->title; ?></label>

								<input type="text" class="form-control" name="proposal_title" id="proposal_title_text" />

								<a href="javascript: void(0);" class="edit-on-click">
									<span class="overlay-edit-icon fa fa-pencil"></span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="content-area" style="background-color : #f0f0f0;">
			<?php echo $content; ?>
		</div>
    </body>

	<br><br>

	<div class="navbar navbar-default navbar-fixed-bottom" style="z-index: 1111 !important;">
		<div class="container">
			<span class="navbar-text" style="float: right; margin-bottom: 10px;">
				<button type="button" class="btn btn-primary" id="save_proposal_as_template_btn" onClick="save_proposal(<?php echo $template_id; ?>, 1);" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Saving Template, please wait...">Save as Template</button>

				&nbsp;&nbsp;

				<a target="_blank" href="<?php echo base_url(); ?>proposals/view/<?php echo $proposal_id; ?>/2" class="btn btn-warning">Preview</a>

				&nbsp;&nbsp;

				<button type="button" class="btn btn-success" onClick="show_proposal_popup();">Send</button>
			</span>
		</div>
	</div>

	<!-- Proposal Save as Template Modal -->
	<!--<div class="modal fade save_proposal_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
					</button>

					 <h4 class="modal-title" id="myModalLabel">Template Title</h4>
				</div>

				<div class="modal-body">
					<form class="form-horizontal" role="form" name="save_proposal_form" id="save_proposal_form">
						<div class="form-group">
							<label class="col-sm-4" for="inputEmail3">Title</label>

							<div class="col-sm-6">
								<input type="text" class="form-control" name="title" id="template_title" placeholder="Enter Template Title" />
							</div>
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="save_proposal_as_template_btn" onClick="save_proposal(<?php echo $template_id; ?>, 1);" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Saving Template, please wait...">Save</button>
				</div>
			</div>
		</div>
	</div>-->

	<!-- Edit Service Modal -->
	<!--<div class="modal edit_service_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
					</button>

					 <h4 class="modal-title" id="myModalLabel">Confirm</h4>
				</div>

				<div class="modal-body">
					Once you will try to update service, the service will be deleted from editor. In order to add same service, you need to reload the page and drag same or different service from right bar.

					<br><br>

					<b>Are you sure you want to edit service?</b>

					<br><br>
				</div>

				<div class="modal-footer">
					<input type="hidden" id="update_service_url" />
					<input type="hidden" id="service_id" />

					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>

					<button type="button" class="btn btn-warning show_update_service">Yes</button>
				</div>
			</div>
		</div>
	</div>-->

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
								<input type="text" class="form-control datetimepicker" name="proposal_validity" id="proposal_validity" placeholder="Proposal valid upto" />
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

    <!-- Static Model for General Purpose Like to Show Process Loading Or General Message Where Modal Can't be Closed Automatic. Need to Close Static Modal Manually After Process Complete -->
	<div class="modal static_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					&nbsp;
				</div>

				<div class="modal-body">
					<p id="static_modal_text"></p>
				</div>

				<div class="modal-footer">
					&nbsp;
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
         var check_service_id = 0;

         function calculate_service_price(proposal_id) {
            var table =  "";
            var row = "";
            var total = 0;

            $('#service_pricing_details').html("");
            
            $('.count_price_div').each(function() {
               var service_id =  $(this).find("span:eq(0)").text();
               var service_name =  $(this).find("span:eq(1)").text();
               var service_description = $(this).find("span:eq(2)").text();
               var service_recurring = $(this).find("span:eq(3)").text();
               var service_price = parseFloat($(this).find("span:eq(4)").text());
               //alert(service_id+' :: '+service_name+' :: '+service_description+' :: '+service_recurring+' :: '+service_price);
               if(check_service_id != 0 && check_service_id == service_id) {
                   row += "<tr><td>"+service_name+"</td><td>"+service_description+"</td><td>"+service_recurring+"</td><td>$"+service_price+"</td></tr>";
                   check_service_id = service_id;
                    total+=service_price;
               } else {
                   row += "<tr><td>"+service_name+"</td><td>"+service_description+"</td><td>"+service_recurring+"</td><td>$"+service_price+"</td></tr>";
                   check_service_id = service_id;
                    total+=service_price;
               }
            });

            $('#price').val(total);

            table = "<table style='width:100%'><thead><tr><th>Service Name</th><th>Occurrance</th><th>Duration</th><th>Cost</th></tr></thead><tbody>"+row+"<tr><td colspan='3'>Total</td><td colspan='4'>$"+total+"</td></tr></tbody></table>";

            $('#service_pricing_details').append(table);

            setTimeout(function() {
                $('#static_modal_text').html('');
                $('.static_modal').modal('hide');

                $(window).scrollTop($('#service_details').offset().top);
                getContent(proposal_id, 1);
                service_id = 0;
            }, 3000);
        }

        function deleteService(service_id) {
            $.ajax({
				type: "POST",
				url: global_base_url+"proposals/deleteService",
				data: {'service_id':service_id, 'proposal_id':<?php echo $proposal_id; ?>,'deleteService' : true},
				cache: false,
				success: function(res) {
					console.log('success');

					if(res == 0) {
						console.log(res);
                    }
				},
				error: function() {
					console.log('error');
				}
			});
        }

		function show_save_proposal_modal() {
			$('.save_proposal_modal').modal('show');
		}

		function save_proposal(template_id, status) {
			/*$(".update_service_link_common").each(function() {
				$(this).remove();
			});*/

			var content = $('#content-area').keditor('getContent');

			//console.log(content);

			var title = $('#proposal_title_text').val();

			$('#save_proposal_as_template_btn').button('loading');

			$.ajax({
				type: "POST",
				url: global_base_url+"proposals/save_proposal_as_template",
				data: {'title' : title, 'content' : content, 'template_id' : template_id, 'status' : status},
				cache: false,
				success: function(res) {
					//alert('success :: '+res);

					if(res != '' && res == 0) {
						//alert('Notify');

						$('#save_proposal_as_template_btn').button('reset');

						//$('#proposal_title').val('');

						//$('.save_proposal_modal').modal('hide');

						$.notify('<strong>Success!</strong> Proposal has been saved as template.', {
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
					}
				},
				error: function() {
					console.log('error');
				}
			});
		}

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

		function getContent(proposal_id, status) {
			//alert('Calling get content');

			/*$(".update_service_link_common").each(function() {
				$(this).remove();
			});*/

			var title = $('#proposal_title_text').val();

			var content = $('#content-area').keditor('getContent');

            var price = $('#price').val();

			//console.log(content);

			$.ajax({
				type: "POST",
				url: global_base_url+"proposals/update_proposal_content",
				data: {'title' : title, 'proposal_id' : proposal_id, 'content' : content, 'status' : status, 'price' : price},
				cache: false,
				success: function(res) {
					console.log('success');

					if(res == 0)
						console.log(res);
				},
				error: function() {
					console.log('error');
				}
			});
		}

        function checkServicesStatus(service_id) {
            $.ajax({
                type: "GET",
                url: global_base_url+"proposals/getproposalServicesStatus/"+<?php echo $proposal_id; ?>+"/"+service_id,
                cache: false,
                success: function(data) {
                    if(data==1) {
                        $('#static_modal_text').html('Please note once service is updated, this page will reload automatic. Please wait while reloads...');
                        $('.static_modal').modal('show');

                        updateServicesStatus(service_id, <?php echo $proposal_id; ?>);
                    }else{
                        setTimeout(function(){
                            checkServicesStatus(service_id);
                        }, 5000);
                    }
                },  
                error: function() {
                    console.log('error');
                }
            });
		}

        function updateServicesStatus(service_id, proposal_id) {
            $.ajax({
                type: "POST",
                url: global_base_url+"proposals/updateproposalServicesStatus",
                cache: false,
                data: {'service_id' : service_id, 'proposal_id' : proposal_id, 'status' : 0, 'updateServicesStatus' : true},
                success: function(result) {
                    if(result==1) {
                       location.reload();
                    }else{
                    }
                },  
                error: function() {
                    console.log('error');
                }
            });
        }

        function addProposalServicesAndTasks(service_id, proposal_id) {
            /*alert($('.service_div-'+service_id).parent().attr('id'));

            var parent_id = $('.service_div-'+service_id).parent().attr('id');

            if(parent_id != '' && parent_id != 'undefined') {
                $('#'+parent_id).remove();
            }*/

            $('.service_div-'+service_id).remove();

            //$('.service_div-'+service_id).parent().remove();

            $.ajax({
				type: "GET",
				url: global_base_url+"proposals/addProposalServicesAndTasks/"+service_id+"/"+proposal_id,
				cache: false,
				success: function(res) {
                    if(res != '' && res == 0) {
                        getProposalServices(proposal_id);
                    }
                },
				error: function() {
					console.log('error');
				}
			});
        }

        function getProposalServices(proposal_id) {
            //alert('getProposalServices called');

            var services = "";

            //$('#service_details').html("");

            $.ajax({
				type: "GET",
				url: global_base_url+"proposals/getproposalServices/"+proposal_id,
				cache: false,
				success: function(data) {
                    if(data != '') {
                        $('#service_details').html("");
                        json = $.parseJSON(data);
                    
                        $.each(json, function (key, data) {
                            var recurring = data.recurring;

                            if(data.recurring==0){
                                recurring ="One Time";
                            }else{
                                recurring ="Recurring";
                            }
                            services += "<div class='count_price_div'><span style='display: none;'>"+data.service_id+"</span><span style='display: none;'>"+data.name+"</span><span style='display: none;'>"+recurring+"</span><span style='display: none;'>"+data.interval+ " "+data.interval_label+"</span><span style='display: none;'>"+data.price+"</span></div><div class='row'><div class='col-s-12 occeurancediv'><span class='occeurance'><b>"+data.name+" ("+data.interval+ " "+data.interval_label+")</b></span></div><div class='col-s-12 durationdiv'><span >"+data.description+"</span></div></div><div class='col-s-12 attachmentdiv'></br><span class='attachment'><div class='attachmentvalue'><a class='update_service_link_common update_service_link-"+data.service_id+"' id='"+data.service_id+"' href='javascript: void();' onclick='showEditProposalServiceModal("+data.service_id+", "+data.proposal_service_id+", "+proposal_id+");'>Edit</a></div></span></div>";
                        });

                        $('#service_details').append(services);

                        calculate_service_price(proposal_id);
                    } else {
                        $('#static_modal_text').html('');
                        $('.static_modal').modal('hide');
                    }
                },
				error: function() {
					console.log('error');
				}
			});
        }

        function showEditProposalServiceModal(service_id, proposal_service_id, proposal_id) {
            //$('#static_modal_text').html('Please note once service is updated, this page will reload automatic. Please wait while reloads...');
            //$('.static_modal').modal('show');

            var url = global_base_url+"services/update/"+proposal_service_id+"/"+proposal_id;

            checkServicesStatus(proposal_service_id);

            window.open(url, '_blank');
        }

        function isValidEmailAddress(emailAddress) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

            return pattern.test(emailAddress);
        }

        //Check Window on Ready Proposal services Status
        $(document).ready(function() {
            $('#static_modal_text').html('Please wait while loading content...');
            $('.static_modal').modal('show');

            setTimeout(function() {
                getProposalServices(<?php echo $proposal_id; ?>);
            }, 5000);
        });

		$(function() {
            /*$(".show_update_service").on("click", function(event) {
                //alert('click is called');

                //event.preventDefault();

                var url = $('#update_service_url').val();

                var service_id = $('#service_id').val();

                $('.service_div-'+service_id).remove();

                //$('.btn-component-delete').trigger('click');

                var service_id  =   $('#service_id').val();
                checkServicesStatus(service_id);

                window.open(url, '_blank');
            });*/

            /*$('#emails').multiple_emails({
                position: "bottom"
            });*/

            //$("#discount").mask("9?9%");

            $('.datetimepicker').datetimepicker({
                format : 'Y/m/d',
                minDate: 0,
                timepicker: false
            });

			$('.send_proposal_modal').on('show.bs.modal', function() {
				$.fn.modal.Constructor.prototype.enforceFocus = function() { };
			});

			$('#content-area').keditor({
				onReady: function() {
					//$("#accordion").accordion();

					//console.log('Callback "onReady"');
				},
				onInitFrame: function(frame, frameHead, frameBody) {
					//console.log('Callback "onInitFrame"', frame, frameHead, frameBody);
				},
				onSidebarToggled: function(isOpened) {
					//console.log('Callback "onSidebarToggled"', isOpened);

					//getContent(<?php echo $proposal_id; ?>, 1);
				},
				onInitContentArea: function(contentArea) {
					//console.log('Callback "onInitContentArea"', contentArea);
				},
				onContentChanged: function(event) {
					//console.log('Callback "onContentChanged"', event);

					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onInitContainer: function(container) {
					//console.log('Callback "onInitContainer"', container);
				},
				onBeforeContainerDeleted: function(event, selectedContainer) {
					//console.log('Callback "onBeforeContainerDeleted"', event, selectedContainer);
				},
				onContainerDeleted: function(event, selectedContainer) {
					//console.log('Callback "onContainerDeleted"', event, selectedContainer);

					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onContainerChanged: function(event, changedContainer) {
					//console.log('Callback "onContainerChanged"', event, changedContainer);

					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onContainerDuplicated: function(event, originalContainer, newContainer) {
					console.log('Callback "onContainerDuplicated"', event, originalContainer, newContainer);

                    getContent(<?php echo $proposal_id; ?>, 1);
				},
				onContainerSelected: function(event, selectedContainer) {
					//console.log('Callback "onContainerSelected"', event, selectedContainer);
				},
				onContainerSnippetDropped: function(event, newContainer, droppedContainer) {
					//console.log('Callback "onContainerSnippetDropped"', event, newContainer, droppedContainer);

                    getContent(<?php echo $proposal_id; ?>, 1);
				},
				onComponentReady: function(component) {
					//console.log('Callback "onComponentReady"', component);
				},
				onInitComponent: function(component) {
					//console.log('Callback "onInitComponent"', component);
				},
				onBeforeComponentDeleted: function(event, selectedComponent) { 
                    deleteService($(".service_div_common").attr('id'));
				},
				onComponentDeleted: function(event, selectedComponent) {
					//console.log('Callback "onComponentDeleted"', event, selectedComponent);

                    getProposalServices(<?php echo $proposal_id; ?>);
				},
				onComponentChanged: function(event, changedComponent) {
					//console.log('Callback "onComponentChanged"', event, changedComponent);

					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onComponentDuplicated: function(event, originalComponent, newComponent) {
					//console.log('Callback "onComponentDuplicated"', event, originalComponent, newComponent);

					//getContent(<?php echo $proposal_id; ?>, 1);
				},
				onComponentSelected: function(event, selectedComponent) {
					//console.log('Callback "onComponentSelected"', event, selectedComponent);

					/*$(".service_div_common span").each(function() {
						service_id = $(this).text();

						$('.update_service_link-'+service_id).click(function() {
							//alert($(this).attr('id')+' :: '+$(this).attr('href'));

							$('#service_id').val($(this).attr('id'));
							$('#update_service_url').val($(this).attr('href'));

                            //$('.edit_service_modal').modal({backdrop: 'static', keyboard: false});
							$('.edit_service_modal').modal('show');
						});
					});*/

					//getContent(<?php echo $proposal_id; ?>, 1);
				},
				onComponentSnippetDropped: function(event, newComponent, droppedComponent) {
					service_id = $(".service_div_common").attr('id');

                    //alert(service_id);

                    if(service_id != '' && service_id > 0 && service_id != 'undefinded') {
                        $('#static_modal_text').html('Please wait...while updating content...');
                        $('.static_modal').modal('show');

                        //alert($(".cke_editable_inline").attr('id'));

                        /*$(".cke_editable_inline").each(function() {
                            alert($(this).attr('id'));

                            if($(this).html() == '<p><br></p>') {
                                $(this).html('');
                            }
                        });*/

                        addProposalServicesAndTasks(service_id, <?php echo $proposal_id; ?>);
                    }
				},
				onBeforeDynamicContentLoad: function(dynamicElement, component) {
					//console.log('Callback "onBeforeDynamicContentLoad"', dynamicElement, component);
				},
				onDynamicContentLoaded: function(dynamicElement, response, status, xhr) {
					//console.log('Callback "onDynamicContentLoaded"', dynamicElement, response, status, xhr);
				},
				onDynamicContentError: function(dynamicElement, response, status, xhr) {
					//console.log('Callback "onDynamicContentError"', dynamicElement, response, status, xhr);
				}
			});
		});
	</script>
</html>