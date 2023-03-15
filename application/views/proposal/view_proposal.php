<?php if($proposal->content != '') { $show = 1; ?>
    <?php if($proposal->expiration_date != '0000-00-00 00:00:00') { ?>
        <?php if(date('Y-m-d', strtotime($proposal->expiration_date)) < date('Y-m-d')) { $show = 0; } ?>
    <?php } else if($proposal->status == 6 || $proposal->status == 7) { $show = 0; } ?>

	<?php if($proposal_type == 0) { ?>
        <?php if($show == 1) { ?>
            <div class="navbar navbar-default navbar-fixed-top" style="z-index: 1111 !important;">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center" style="margin-top: 6px;">
                                <button class="btn btn-primary "onclick="acceptProposal(<?php echo $proposal_id; ?>)" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Please wait...">Accept</button>

                                &nbsp;&nbsp;&nbsp;&nbsp;

                                <button class="btn btn-warning" onclick="declineProposal(<?php echo $proposal_id; ?>)" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Please wait..." >Decline</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br><br>
        <?php } ?>

		<div class="container">
            <div class="form-group row">
                <div class="col-xs-8" style="padding: 8px 0px 0px 500px;">
                    <label id="proposal_title_label" style="font-size: 22px; font-weight: bold;"><?php echo $proposal->title; ?></label>
                </div>
            </div>

			<?php echo $proposal->content; ?>
		</div>
	<?php } else if($proposal_type == 1) { ?>
		<div id="blackBar">
			<div class="row">
				<div class="col-sm-12">
					<div class="text-center">
						<button class="btn btn-primary " onclick="acceptProposal(<?php echo $proposal_id; ?>)" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Please wait...">Accept</button>

						&nbsp;&nbsp;&nbsp;&nbsp;

						<button class="btn btn-warning" onclick="declineProposal(<?php echo $proposal_id; ?>)" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Please wait...">Decline</button>
					</div>
				</div>
			</div>
		</div>

		<br><br>

		<div id="content-area" style="background-color : #f0f0f0;">
			<?php echo $proposal->content; ?>
		</div>
	<?php } else if($proposal_type == 2) { ?>
		<div class="container">
            <div class="form-group row">
                <div class="col-xs-8" style="padding: 8px 0px 0px 500px;">
                    <label id="proposal_title_label" style="font-size: 22px; font-weight: bold;"><?php echo $proposal->title; ?></label>
                </div>
            </div>

			<?php echo $proposal->content; ?>
		</div>
	<?php } ?>
<?php } ?>