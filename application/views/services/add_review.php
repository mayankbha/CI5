<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-th-list"></span>
<!--        --><?php //echo lang("ctn_1215") ?>
        Selected Services
    </div>
    <div class="db-header-extra form-inline"> 




</div>
</div>
    <?php echo form_open_multipart(site_url("projects/add_project_by_service"), array("class" => "form-horizontal")) ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading">
                    <h4>
                    Project Name<strong style="color: red;">*</strong>
                    </h4>
                </label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="name" value="" style="" required autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="p-in" class="col-md-3 label-heading">
                    <h4>
                        Date Start<strong style="color: red;">*</strong>
                    </h4>
                </label>
                <div class="col-md-6">
                    <input type="text" name="date_start" class="form-control datepicker" required>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <tr class="table-header">
            <th>Service Name</th>
            <th>Price</th>
            <th>Hours to Complete</th>
            <th>Description</th>
            <th>Attachment</th>
            <th>Notes</th>
        </tr>
        <tbody>
        <?php foreach($services as $service_id => $service){ ?>
            <tr>
                <td>
                    <h4><?php echo $service['name']; ?></h4>
                    <input type="hidden" value="<?php echo $service_id;?>" name="services[]" />
                </td>
                <td>$<?php echo $service['price']; ?></td>
                <td><?php echo str_replace('.00', '',$service['hours']); ?> hours</td>
                <td>
                    <?php if (!empty($service['description'])) : ?>
                        <?php echo $service['description']; ?>
                    <?php else : ?>
                        <?php echo "N/A"; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($service['filename']) && file_exists($_SERVER['DOCUMENT_ROOT'].'/mams/uploads/services/'.$service['encrypt'])) : ?>
                    <?php echo "<a href='".base_url()."uploads/services/".$service['encrypt']."' download>".$service['filename']."</a>"; ?>
                    <?php else : ?>
                    <?php echo "N/A"; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($service['notes'])) : ?>
                        <?php echo $service['notes']; ?>
                    <?php else : ?>
                        <?php echo "N/A"; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <h4>Tasks</h4>

                    <ul>
                    <?php foreach($service['tasks'] as $task){ ?>
                        <?php
                            if (!empty($task['name']) && $task['is_subtask'] == 0) {
                                echo '<li>'.$task['name'].'</li>';
                            }
                        ?>
                    <?php } ?>
                    </ul>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
    <input type="submit" class="btn btn-lg btn-primary" value="Submit"/>
    </form>
<!--<div class="table-responsive">-->
<!--<table id="services-table" class="table table-bordered table-striped table-hover">-->
<!--<thead>-->
<!--<tr class="table-header"><td>--><?php //echo lang("ctn_11") ?><!--</td><td>--><?php //echo lang("ctn_794") ?><!--</td><td>--><?php //echo lang("ctn_261") ?><!--</td><td>--><?php //echo lang("ctn_52") ?><!--</td></tr>-->
<!--</thead>-->
<!--<tbody>-->
<!---->
<!--</tbody>-->
<!--</table>-->
<!--</div>-->

</div>
<script type="text/javascript">
$(document).ready(function() {

//   var st = $('#search_type').val();
//    var table = $('#services-table').DataTable({
//        "dom" : "<'row'<'col-sm-12'tr>>" +
//                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
//      "processing": false,
//        "pagingType" : "full_numbers",
//        "pageLength" : 15,
//        "serverSide": true,
//        "orderMulti": false,
//        "order": [
//
//        ],
//        "columns": [
//        null,
//        null,
//        null,
//        { "orderable": false }
//    ],
//        "ajax": {
//            url : "<?php //echo site_url("services/services_page") ?>//",
//            type : 'GET',
//            data : function ( d ) {
//                d.search_type = $('#search_type').val();
//            }
//        },
//        "drawCallback": function(settings, json) {
//        $('[data-toggle="tooltip"]').tooltip();
//      }
//    });
//    $('#form-search-input').on('keyup change', function () {
//    table.search(this.value).draw();
//});

} );
function change_search(search) 
    {
    	var options = [
    		"search-like", 
    		"search-exact", 
    		"form-exact",
    		"assigned-exact",
    	];
    	set_search_icon(options[search], options);
        $('#search_type').val(search);
        $( "#form-search-input" ).trigger( "change" );
    }

function set_search_icon(icon, options) 
    {
    	for(var i = 0; i<options.length;i++) {
    		if(options[i] == icon) {
    			$('#' + icon).fadeIn(10);
    		} else {
    			$('#' + options[i]).fadeOut(10);
    		}
    	}
    }
</script>