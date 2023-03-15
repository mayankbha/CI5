
<div class="white-area-content">
	<div class="db-header clearfix">
		<div class="page-header-title"><span class="glyphicon glyphicon-tasks"></span> <?php echo 'Proposals'; //echo lang("ctn_820") ?></div>

		<div class="db-header-extra form-inline">
			<div class="form-group has-feedback no-margin">
				<div class="input-group">
					<input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_354") ?>" id="form-search-input" />

					<div class="input-group-btn">
						<input type="hidden" id="search_type" value="0" />

						<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</button>

						<ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
							<li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_355") ?></a></li>
							<li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_356") ?></a></li>
							<li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="title-exact"></span> <?php echo lang("ctn_823") ?></a></li>
						</ul>
					</div><!-- /btn-group -->
				</div>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<div role="tabpanel">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" <?php if($active_status == 0 && $active_status != 'templates') { ?>class="active"<?php } ?>><a href="#all" aria-controls="all" role="tab" data-toggle="tab" id="all_length">All (<?php echo count($proposals); ?>)</a></li>

				<li role="presentation" <?php if($active_status == 1 && $active_status != 'templates') { ?>class="active"<?php } ?>><a href="#draft" aria-controls="draft" role="tab" data-toggle="tab" id="draft_length">Draft ()</a></li>

				<li role="presentation" <?php if($active_status == 2 && $active_status != 'templates') { ?>class="active"<?php } ?>><a href="#sent" aria-controls="sent" role="tab" data-toggle="tab" id="sent_length">Sent ()</a></li>

				<!--<li role="presentation" <?php if($active_status == 4 && $active_status != 'templates') { ?>class="active"<?php } ?>><a href="#received" aria-controls="received" role="tab" data-toggle="tab" id="received_length">Received ()</a></li>-->

				<li role="presentation" <?php if($active_status == 6 && $active_status != 'templates') { ?>class="active"<?php } ?>><a href="#accepted" aria-controls="accepted" role="tab" data-toggle="tab" id="accepted_length">Accepted ()</a></li>

				<li role="presentation" <?php if($active_status == 7 && $active_status != 'templates') { ?>class="active"<?php } ?>><a href="#declined" aria-controls="declined" role="tab" data-toggle="tab" id="declined_length">Declined ()</a></li>

				<li role="presentation" <?php if($active_status == 'templates') { ?>class="active"<?php } ?>><a href="#templates" aria-controls="templates" role="tab" data-toggle="tab" id="templates_length">Templates ()</a></li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane <?php if($active_status == 0 && $active_status != 'templates') { ?>active<?php } ?>" id="all">
					<table id="proposals-table-all" class="table table-bordered table-striped table-hover">
						<thead>
							<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_847") ?></td><td><?php echo 'Receiver Email'; //echo lang("ctn_1413") ?></td><td><?php echo 'Subtotal'; //echo lang("ctn_1414") ?></td><td><?php echo 'Discount Offered'; //echo lang("ctn_848") ?></td><td><?php echo 'Total'; //echo lang("ctn_825") ?></td><td><?php echo 'Expiration Date'; //echo lang("ctn_825") ?></td><td><?php echo 'Payment Status'; //echo lang("ctn_825") ?></td><td><?php echo 'Status'; //echo lang("ctn_825") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
						</thead>

						<tbody></tbody>
					</table>
				</div>

				<div role="tabpanel" class="tab-pane <?php if($active_status == 1 && $active_status != 'templates') { ?>active<?php } ?>" id="draft">
					<table id="proposals-table-draft" class="table table-bordered table-striped table-hover">
						<thead>
							<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_847") ?></td><td><?php echo 'Receiver Email'; //echo lang("ctn_1413") ?></td><td><?php echo 'Subtotal'; //echo lang("ctn_1414") ?></td><td><?php echo 'Discount Offered'; //echo lang("ctn_848") ?></td><td><?php echo 'Total'; //echo lang("ctn_825") ?></td><td><?php echo 'Expiration Date'; //echo lang("ctn_825") ?></td><td><?php echo 'Payment Status'; //echo lang("ctn_825") ?></td><td><?php echo 'Status'; //echo lang("ctn_825") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
						</thead>

						<tbody></tbody>
					</table>
				</div>

				<div role="tabpanel" class="tab-pane <?php if($active_status == 2 && $active_status != 'templates') { ?>active<?php } ?>" id="sent">
					<div role="tabpanel">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" <?php if($active_status == 2 && $active_status != 'templates') { ?>class="active"<?php } ?>><a aria-controls="sent_pending_review" role="tab" data-toggle="tab" href="#sent_pending_review" id="sent_pending_review_length">Not Viewed ()</a></li>

							<li role="presentation" <?php if($active_status == 3 && $active_status != 'templates') { ?>class="active"<?php } ?>><a aria-controls="sent_pending_approval" role="tab" data-toggle="tab" href="#sent_pending_approval" id="sent_pending_approval_length">Viewed ()</a></li>
						</ul>

						<div class="tab-content">
							<div role="tabpanel" class="tab-pane <?php if($active_status == 2 && $active_status != 'templates') { ?>active<?php } ?>" id="sent_pending_review">
								<table id="proposals-table-sent-pending-review" class="table table-bordered table-striped table-hover">
									<thead>
										<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_847") ?></td><td><?php echo 'Receiver Email'; //echo lang("ctn_1413") ?></td><td><?php echo 'Subtotal'; //echo lang("ctn_1414") ?></td><td><?php echo 'Discount Offered'; //echo lang("ctn_848") ?></td><td><?php echo 'Total'; //echo lang("ctn_825") ?></td><td><?php echo 'Expiration Date'; //echo lang("ctn_825") ?></td><td><?php echo 'Payment Status'; //echo lang("ctn_825") ?></td><td><?php echo 'Status'; //echo lang("ctn_825") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
									</thead>

									<tbody></tbody>
								</table>
							</div>

							<div role="tabpanel" class="tab-pane <?php if($active_status == 3 && $active_status != 'templates') { ?>active<?php } ?>" id="sent_pending_approval">
								<table id="proposals-table-sent-pending-approval" class="table table-bordered table-striped table-hover">
									<thead>
										<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_847") ?></td><td><?php echo 'Receiver Email'; //echo lang("ctn_1413") ?></td><td><?php echo 'Subtotal'; //echo lang("ctn_1414") ?></td><td><?php echo 'Discount Offered'; //echo lang("ctn_848") ?></td><td><?php echo 'Total'; //echo lang("ctn_825") ?></td><td><?php echo 'Expiration Date'; //echo lang("ctn_825") ?></td><td><?php echo 'Payment Status'; //echo lang("ctn_825") ?></td><td><?php echo 'Status'; //echo lang("ctn_825") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
									</thead>

									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!--<div role="tabpanel" class="tab-pane <?php if($active_status == 4 && $active_status != 'templates') { ?>active<?php } ?>" id="received">
					<div role="tabpanel">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" <?php if($active_status == 4 && $active_status != 'templates') { ?>class="active"<?php } ?>><a aria-controls="received_pending_review" role="tab" data-toggle="tab" href="#received_pending_review" id="received_pending_review_length">Not Viewed ()</a></li>

							<li role="presentation" <?php if($active_status == 5 && $active_status != 'templates') { ?>class="active"<?php } ?>><a aria-controls="received_pending_approval" role="tab" data-toggle="tab" href="#received_pending_approval" id="received_pending_approval_length">Viewed ()</a></li>
						</ul>

						<div class="tab-content">
							<div role="tabpanel" class="tab-pane <?php if($active_status == 4 && $active_status != 'templates') { ?>active<?php } ?>" id="received_pending_review">
								<table id="proposals-table-received-pending-review" class="table table-bordered table-striped table-hover">
									<thead>
										<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_847") ?></td><td><?php echo 'Price'; //echo lang("ctn_1413") ?></td><td><?php echo 'Expiration Date'; //echo lang("ctn_1414") ?></td><td><?php echo 'Status'; //echo lang("ctn_848") ?></td><td><?php echo 'Payment Status'; //echo lang("ctn_825") ?></td><td><?php echo 'Proposal Type'; //echo lang("ctn_849") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
									</thead>

									<tbody></tbody>
								</table>
							</div>

							<div role="tabpanel" class="tab-pane <?php if($active_status == 5 && $active_status != 'templates') { ?>active<?php } ?>" id="received_pending_approval">
								<table id="proposals-table-received-pending-approval" class="table table-bordered table-striped table-hover">
									<thead>
										<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_847") ?></td><td><?php echo 'Price'; //echo lang("ctn_1413") ?></td><td><?php echo 'Expiration Date'; //echo lang("ctn_1414") ?></td><td><?php echo 'Status'; //echo lang("ctn_848") ?></td><td><?php echo 'Payment Status'; //echo lang("ctn_825") ?></td><td><?php echo 'Proposal Type'; //echo lang("ctn_849") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
									</thead>

									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>-->

				<div role="tabpanel" class="tab-pane <?php if($active_status == 6 && $active_status != 'templates') { ?>active<?php } ?>" id="accepted">
					<table id="proposals-table-accepted" class="table table-bordered table-striped table-hover">
						<thead>
							<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_847") ?></td><td><?php echo 'Receiver Email'; //echo lang("ctn_1413") ?></td><td><?php echo 'Subtotal'; //echo lang("ctn_1414") ?></td><td><?php echo 'Discount Offered'; //echo lang("ctn_848") ?></td><td><?php echo 'Total'; //echo lang("ctn_825") ?></td><td><?php echo 'Payment Status'; //echo lang("ctn_849") ?></td><td><?php echo 'Status'; //echo lang("ctn_849") ?></td><td><?php echo 'Setup Service'; //echo lang("ctn_849") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
						</thead>

						<tbody></tbody>
					</table>
				</div>

				<div role="tabpanel" class="tab-pane <?php if($active_status == 7 && $active_status != 'templates') { ?>active<?php } ?>" id="declined">
					<table id="proposals-table-declined" class="table table-bordered table-striped table-hover">
						<thead>
							<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_847") ?></td><td><?php echo 'Receiver Email'; //echo lang("ctn_1413") ?></td><td><?php echo 'Subtotal'; //echo lang("ctn_1414") ?></td><td><?php echo 'Discount Offered'; //echo lang("ctn_848") ?></td><td><?php echo 'Total'; //echo lang("ctn_825") ?></td><td><?php echo 'Expiration Date'; //echo lang("ctn_825") ?></td><td><?php echo 'Payment Status'; //echo lang("ctn_825") ?></td><td><?php echo 'Status'; //echo lang("ctn_825") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
						</thead>

						<tbody></tbody>
					</table>
				</div>

				<div role="tabpanel" class="tab-pane <?php if($active_status == 'templates') { ?>active<?php } ?>" id="templates">
					<table id="templates-table" class="table table-bordered table-striped table-hover">
						<thead>
							<tr class="table-header"><td><?php echo 'Title'; //echo lang("ctn_1413") ?></td><td><?php echo 'Status'; //echo lang("ctn_1414") ?></td><td><?php echo 'Created Date'; //echo lang("ctn_849") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
						</thead>

						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		var sent_length = 0;
		var received_length = 0;

		var st = $('#search_type').val();

		var proposals_table_all = $('#proposals-table-all').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[7, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							null,
							null,
							null,
                            null,
                            null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_proposals/0"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						proposals_table_all.search(this.value).draw();
					});

		var proposals_table_draft = $('#proposals-table-draft').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[7, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							null,
							null,
							null,
                            null,
                            null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_proposals/1"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							var draft_length = settings.aoData.length;

							$('#draft_length').html('Draft ('+draft_length+')');

							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						proposals_table_draft.search(this.value).draw();
					});
	
		var proposals_table_sent_pending_review = $('#proposals-table-sent-pending-review').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[7, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							null,
							null,
							null,
                            null,
                            null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_proposals/2"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							var sent_pending_review_length = settings.aoData.length;

							$('#sent_pending_review_length').html('Not Viewed ('+sent_pending_review_length+')');

							sent_length =  parseInt(sent_length) + parseInt(sent_pending_review_length);

							$('#sent_length').html('Sent ('+sent_length+')');

							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						proposals_table_sent_pending_review.search(this.value).draw();
					});

		var proposals_table_sent_pending_approval = $('#proposals-table-sent-pending-approval').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[7, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							null,
							null,
							null,
                            null,
                            null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_proposals/3"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							var sent_pending_approval_length = settings.aoData.length;

							$('#sent_pending_approval_length').html('Viewed ('+sent_pending_approval_length+')');

							sent_length =  parseInt(sent_length) + parseInt(sent_pending_approval_length);

							$('#sent_length').html('Sent ('+sent_length+')');

							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						proposals_table_sent_pending_approval.search(this.value).draw();
					});

		var proposals_table_received_pending_review = $('#proposals-table-received-pending-review').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[4, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							null,
							null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_proposals/4"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							var received_pending_review_length = settings.aoData.length;

							$('#received_pending_review_length').html('Not Viewed ('+received_pending_review_length+')');

							received_length =  parseInt(received_length) + parseInt(received_pending_review_length);

							$('#received_length').html('Received ('+received_length+')');

							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						proposals_table_received_pending_review.search(this.value).draw();
					});

		var proposals_table_received_pending_approval = $('#proposals-table-received-pending-approval').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[4, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							null,
							null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_proposals/5"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							var received_pending_approval_length = settings.aoData.length;

							$('#received_pending_approval_length').html('Viewed ('+received_pending_approval_length+')');

							received_length =  parseInt(received_length) + parseInt(received_pending_approval_length);

							$('#received_length').html('Received ('+received_length+')');

							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						proposals_table_received_pending_approval.search(this.value).draw();
					});
		
		var proposals_table_accepted = $('#proposals-table-accepted').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[7, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							null,
							null,
							null,
                            null,
                            null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_proposals/6"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							var accepted_length = settings.aoData.length;

							$('#accepted_length').html('Accepted ('+accepted_length+')');

							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						proposals_table_accepted.search(this.value).draw();
					});
		
		var proposals_table_declined = $('#proposals-table-declined').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[7, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							null,
							null,
							null,
                            null,
                            null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_proposals/7"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							var declined_length = settings.aoData.length;

							$('#declined_length').html('Declined ('+declined_length+')');

							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						proposals_table_declined.search(this.value).draw();
					});

		var templates_table = $('#templates-table').DataTable({
						"dom" : "<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
						"processing": false,
						"pagingType" : "full_numbers",
						"pageLength" : 15,
						"serverSide": true,
						"orderMulti": false,
						"order": [
							[2, "asc"]
						],
						"columns": [
							null,
							null,
							null,
							{ "orderable": false }
						],
						"ajax": {
							url: "<?php echo site_url("proposals/get_templates"); ?>",
							type: 'GET',
							data: function(d) {
								//console.log(d);

								d.search_type = $('#search_type').val();
							}
						},
						"drawCallback": function(settings, json) {
							var templates_length = settings.aoData.length;

							$('#templates_length').html('Templates ('+templates_length+')');

							$('[data-toggle="tooltip"]').tooltip();
						}
					});

					$('#form-search-input').on('keyup change', function() {
						templates_table.search(this.value).draw();
					});
	});

	function change_search(search) {
		var options = [
						"search-like",
						"search-exact",
						"title-exact",
					];

		set_search_icon(options[search], options);

		$('#search_type').val(search);
		$( "#form-search-input" ).trigger("change");
	}

	function set_search_icon(icon, options) {
		for(var i = 0; i<options.length; i++) {
			if(options[i] == icon) {
				$('#' + icon).fadeIn(10);
			} else {
				$('#' + options[i]).fadeOut(10);
			}
		}
	}
</script>