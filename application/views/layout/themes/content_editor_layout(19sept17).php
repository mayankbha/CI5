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

		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />

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

		<script type="text/javascript" src="<?php echo base_url(); ?>scripts/bootstrap-tagsinput.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>styles/bootstrap-tagsinput.css" />

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
					}
				});

				$('#proposal_title_text').focusout(function() {
					var p_text = $('#proposal_title_text').val();

					$('#proposal_title_label').text(p_text);

					$('#proposal_title_text').hide();
					$('#proposal_title_label').show();

					$('.edit-on-click').show();
				});
			});
		</script>

		<style>
			#ui-datepicker-div {z-index: 1111 !important;}
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
	<div class="modal fade edit_service_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

					<button type="button" class="btn btn-warning" data-dismiss="modal" onclick="show_update_service();">Yes</button>
				</div>
			</div>
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
						<div class="form-group">
							<label class="col-sm-12" for="inputEmail3">
								Receipient Email Address (Add multiple email address by Enter button)
							</label>

							<div class="col-sm-10">
								<input type="text" class="form-control" name="emails" id="emails" data-role="tagsinput" value="" />
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
								<input type="text" class="form-control" name="discount" id="discount" placeholder="Discount" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4" for="inputPassword3">Proposal Valid Upto</label>

							<div class="col-sm-6">
								<input type="text" class="form-control datepicker" name="proposal_validity" id="proposal_validity" placeholder="Proposal valid upto" />
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

	<script type="text/javascript">
       
         var check_service_id = 0;
         function calculate_service_price() {
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

            table=  "<table style='width:100%'><thead><tr><th>Service Name</th><th>Occurrance</th><th>Duration</th><th>Cost</th></tr></thead><tbody>"+row+"<tr><td colspan='3'>Total</td><td colspan='4'>$"+total+"</td></tr></tbody></table>";
           $('#service_pricing_details').append(table);
           console.log(service_id);
        }

        function deleteService(service_id){
            $.ajax({
				type: "POST",
				url: global_base_url+"proposals/deleteService",
				data: {'service_id':service_id, 'proposal_id':<?php echo $proposal_id; ?>,'deleteService' : true},
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

		function show_update_service() {
			var url = $('#update_service_url').val();

			var service_id = $('#service_id').val();

			$('.service_div-'+service_id).remove();

			//$('.btn-component-delete').trigger('click');

			window.open(url, '_blank');
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

			$('#'+btn_id).button('loading');

			$.ajax({
				type: "POST",
				url: global_base_url+"proposals/send_proposal",
				data: {'proposal_id' : proposal_id, 'proposal_type' : proposal_type, 'emails' : emails, 'discount' : discount, 'proposal_validity' : proposal_validity},
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
					}
				},
				error: function() {
					console.log('error');
				}
			});
		}

		function getContent(proposal_id, status) {
			//alert('Calling get content');

			$(".update_service_link_common").each(function() {
				$(this).remove();
			});

			var title = $('#proposal_title_text').val();

			var content = $('#content-area').keditor('getContent');

			//console.log(content);

			$.ajax({
				type: "POST",
				url: global_base_url+"proposals/update_proposal_content",
				data: {'title' : title, 'proposal_id' : proposal_id, 'content' : content, 'status' : status},
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

		$(function() {
			$('.send_proposal_modal').on('show.bs.modal', function() {
				$.fn.modal.Constructor.prototype.enforceFocus = function() { };
			});

			$('.datepicker').datepicker({
				dateFormat: 'dd-mm-yy',
				changeMonth: true,
				changeYear: true,
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

					getContent(<?php echo $proposal_id; ?>, 1);
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

					//getContent(<?php echo $proposal_id; ?>, 1);
				},
				onContainerChanged: function(event, changedContainer) {
					//console.log('Callback "onContainerChanged"', event, changedContainer);

					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onContainerDuplicated: function(event, originalContainer, newContainer) {
					//console.log('Callback "onContainerDuplicated"', event, originalContainer, newContainer);
				},
				onContainerSelected: function(event, selectedContainer) {
					//console.log('Callback "onContainerSelected"', event, selectedContainer);
				},
				onContainerSnippetDropped: function(event, newContainer, droppedContainer) {
					//console.log('Callback "onContainerSnippetDropped"', event, newContainer, droppedContainer);
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
                    calculate_service_price();
					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onComponentChanged: function(event, changedComponent) {
					//console.log('Callback "onComponentChanged"', event, changedComponent);

					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onComponentDuplicated: function(event, originalComponent, newComponent) {
					//console.log('Callback "onComponentDuplicated"', event, originalComponent, newComponent);

					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onComponentSelected: function(event, selectedComponent) {
					//console.log('Callback "onComponentSelected"', event, selectedComponent);

					$(".service_div_common span").each(function() {
						service_id = $(this).text();

						$('.update_service_link-'+service_id).click(function() {
							//alert($(this).attr('id')+' :: '+$(this).attr('href'));

							$('#service_id').val($(this).attr('id'));
							$('#update_service_url').val($(this).attr('href'));

							$('.edit_service_modal').modal('show');
						});
					});

					getContent(<?php echo $proposal_id; ?>, 1);
				},
				onComponentSnippetDropped: function(event, newComponent, droppedComponent) {
					//console.log('Callback "onComponentSnippetDropped"', event, newComponent, droppedComponent);

					//console.log('Callback "onComponentSnippetDropped"', newComponent);

					//getContent(<?php echo $proposal_id; ?>, 0);
 
                    //Check Service Cost 
                    calculate_service_price();
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