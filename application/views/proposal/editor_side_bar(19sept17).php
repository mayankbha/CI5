<!-------------------------------------------------------------------------------------------------->
<!-- Containers -->
<!-------------------------------------------------------------------------------------------------->
<div data-type="container" data-preview="<?php echo base_url(); ?>images/editor_side_bar/row_12.png" data-keditor-title="1 column" data-keditor-categories="1 column">
	<div class="row">
		<div class="col-lg-12" data-type="container-content">
			<div class="inline-content"></div>
		</div>
	</div>
</div>

<div data-type="container" data-preview="<?php echo base_url(); ?>images/editor_side_bar/row_6_6.png" data-keditor-title="2 columns (50% - 50%)" data-keditor-categories="2 columns">
    <div class="row">
		<div class="inline-content">
			<div class="col-sm-6" data-type="container-content"></div>
			<div class="col-sm-6" data-type="container-content"></div>
		</div>
    </div>
</div>

<div data-type="container" data-preview="<?php echo base_url(); ?>images/editor_side_bar/row_4_8.png" data-keditor-title="2 columns (33% - 67%)" data-keditor-categories="2 columns">
	<div class="row">
		<div class="inline-content">
			<div class="col-sm-4" data-type="container-content"></div>
			<div class="col-sm-8" data-type="container-content"></div>
		</div>
	</div>
</div>

<div data-type="container" data-preview="<?php echo base_url(); ?>images/editor_side_bar/row_8_4.png" data-keditor-title="2 columns (67% - 33%)" data-keditor-categories="2 columns">
	<div class="row">
		<div class="inline-content">
			<div class="col-sm-8" data-type="container-content"></div>
			<div class="col-sm-4" data-type="container-content"></div>
		</div>
	</div>
</div>

<div data-type="container" data-preview="<?php echo base_url(); ?>images/editor_side_bar/row_4_4_4.png" data-keditor-title="3 columns (33% - 33% - 33%)" data-keditor-categories="3 columns">
	<div class="row">
		<div class="inline-content">
			<div class="col-sm-4" data-type="container-content"></div>
			<div class="col-sm-4" data-type="container-content"></div>
			<div class="col-sm-4" data-type="container-content"></div>
		</div>
	</div>
</div>

<div data-type="container" data-preview="<?php echo base_url(); ?>images/editor_side_bar/row_3_6_3.png" data-keditor-title="3 columns (25% - 50% - 35%)" data-keditor-categories="3 columns">
	<div class="row">
		<div class="inline-content">
			<div class="col-sm-3" data-type="container-content"></div>
			<div class="col-sm-6" data-type="container-content"></div>
			<div class="col-sm-3" data-type="container-content"></div>
		</div>
	</div>
</div>

<div data-type="container" data-preview="<?php echo base_url(); ?>images/editor_side_bar/row_3_3_3_3.png" data-keditor-title="4 columns (25% - 25% - 25% - 25%)" data-keditor-categories="4 columns">
	<div class="row">
		<div class="inline-content">
			<div class="col-sm-3" data-type="container-content"></div>
			<div class="col-sm-3" data-type="container-content"></div>
			<div class="col-sm-3" data-type="container-content"></div>
			<div class="col-sm-3" data-type="container-content"></div>
		</div>
	</div>
</div>

<!-------------------------------------------------------------------------------------------------->
<!-- Components -->
<!-------------------------------------------------------------------------------------------------->
<div data-type="component-text" data-preview="Heading 1" data-keditor-title="Heading 1" data-keditor-categories="Text;Heading">
	<div class="inline-content">
		<h1>Heading text 1</h1>
	</div>
</div>

<div data-type="component-text" data-preview="Heading 2" data-keditor-title="Heading 2" data-keditor-categories="Text;Heading">
	<div class="inline-content">
		<h2>Heading text 2</h2>
	</div>
</div>

<div data-type="component-text" data-preview="Paragraph" data-keditor-title="Paragraph" data-keditor-categories="Text;Dynamic component">
	<div class="inline-content">
		<blockquote><em>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro labore architecto fuga tempore omnis aliquid, rerum numquam deleniti ipsam earum velit aliquam deserunt, molestiae officiis mollitia accusantium suscipit fugiat esse magnam eaque cumque, iste corrupti magni?</em><br></blockquote>
	</div>
</div>

<div data-type="component-photo" data-preview="Image" data-keditor-title="Image" data-keditor-categories="Media;Photo">
	<div class="photo-panel">
		<img src="<?php echo base_url(); ?>images/placeholder_600x450.jpg" width="100%" height="" />
	</div>
</div>

<?php if(!empty($services)) { ?>
	<h1>Services : </h1>

	<br>

	<?php foreach($services as $service) { ?> 
		<div data-type="component-text" data-preview="<?php echo $service['name']; ?>" data-keditor-title="<?php echo $service['name']; ?>" data-keditor-categories="Text;Heading">
			<div class="inline-content service_div_common service_div-<?php echo $service['id']; ?>" id="<?php echo $service['id']; ?>">
				<span style="display: none;"><?php echo $service['id']; ?></span>
                
                <?php $total = 0; foreach($service['tasks'] as $task) {
                             $total += $task['price']; 
                } ?>
               <div class="count_price_div">
                    <span style="display: none;"><?php echo $service['id']; ?></span> 
                    <span style="display: none;"><?php echo $service['name']; ?></span> 
                    <span style="display: none;"><?php echo $service['description']; ?></span> 
                    <span style="display: none;"><?php  if($service['occurrence'] == 0) { echo "One Time";} else {"Recurring";} ?></span> 
                    <span style="display: none;"><?php echo $total; ?></span>
                </div>
				<div class="row">
					<div class="col-s-12 occeurancediv">
						<span class="occeurance">
							<b>[[Name :]]</b>

							<div class="occeurancevalue">
								<?php echo '[['.$service['name'].']]'; ?>
							</div>
						</span>
					</div>

					<!--<div class="col-s-12 occeurancediv">
						<span class="occeurance">
							<b>Occurrence :</b>

							<div class="occeurancevalue">
								<?php/* if($service['occurrence'] == 0) { ?>
									One Time
								<?php } else { ?>
									Recurring
								<?php }*/ ?>
							</div>
						</span>
					</div>-->

					<div class="col-s-12 durationdiv">
						<span class="duration">
							<b>Duration : </b>

							<div class="durationvalue">
								<?php echo $service['interval'].' '.$service['interval_label']; ?>
							</div>
						</span>
					</div>

					<!--<div class="col-s-12 descriptiondiv">
						<span class="description">
							<b>Description : </b>

							<div class="descriptiontext">
								<?php /*echo $service['description']; ?>
							</div>
						</span>
					</div>

					<div class="col-s-12 taskdiv">
						<span class="task">
							<b>Task : </b>

							<?php $total = 0; foreach($service['tasks'] as $task) { ?>
								<?php $total += $task['price']; ?>

								<div class="tasktext">
									<div><?php echo $task['name']; ?></div>

									<div class="accounting">$<?php echo $task['price']; ?>  | <?php echo $task['hours']; ?> Hours to complete | 0 Files</div>
								</div>
							<?php } ?>
						</span>
					</div>

					<div class="col-s-12 pricediv">
						<span class="price">
							<b>Price : </b>

							<div class="pricetext">
								$<?php echo number_format($total, 2); */?>
							</div>
						</span>
					</div>

					<div class="col-s-12 attachmentdiv">
						<span class="attachment">
							<b>Attachment : </b>

							<div class="attachmentvalue">
								3 Files
							</div>
						</span>
					</div>-->
				</div>

				<?php if($service['user_id'] == $logged_user->ID) { ?>
					<div class="col-s-12 attachmentdiv">
                        </br>
                        <span class="attachment">
                            <div class="attachmentvalue">
                                <a class="update_service_link_common update_service_link-<?php echo $service['id']; ?>" id="<?php echo $service['id']; ?>" href="<?php echo base_url() ?>services/update/<?php echo $service['id'].'/'.$proposal_id; ?>">Edit</a>
                            </div>
                        </span>
                    </div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
<?php } ?>