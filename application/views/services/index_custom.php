<div class="white-area-content">

    <div class="db-header clearfix">
        <div class="page-header-title"> <span class="glyphicon glyphicon-th-list"></span> <?php echo lang("ctn_1215") ?></div>
        <div class="db-header-extra form-inline">

            <div class="form-group has-feedback no-margin">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_354") ?>" id="form-search-input" />
                    <div class="input-group-btn">
                        <input type="hidden" id="search_type" value="0">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                        <ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
                            <li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_355") ?></a></li>
                            <li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok no-display" id="search-exact"></span> <?php echo lang("ctn_356") ?></a></li>
                            <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="form-exact"></span> <?php echo lang("ctn_812") ?></a></li>
                            <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="assigned-exact"></span> <?php echo lang("ctn_794") ?></a></li>
                        </ul>
                    </div><!-- /btn-group -->
                </div>
            </div>


            <!--    <a href="--><?php //echo site_url("services/add") ?><!--" class="btn btn-primary btn-sm">--><?php //echo lang("ctn_1221") ?><!--</a>-->
            <a href="<?php echo site_url("services/create") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_1221") ?></a>
        </div>
    </div>
    <?php echo form_open(site_url("services/add_review"), array("class" => "form-horizontal", "id" => "form_review")) ?>
    <table class="table table-bordered table-striped table-hover services-tbl">
        <tr class="table-header">
            <th>Service Name</th>
            <th>Price</th>
            <th>Hours to Complete</th>
            <th style="text-align: center"></th>
            <th style="width:10px; text-align: center">Option</th>
        </tr>
        <tbody>
        <?php foreach($services as $service){ ?>
            <?php if (!empty($service['name'])) : ?>
                <tr class="services-tr">
                    <td><h4 class="d-name"><?php echo $service['name']; ?></h4><a href="#" class="" data-toggle="modal" data-target="#desc-modal-<?php echo $service['id'] ?>">View Details</a></td>
                    <td>$<?php echo $service['price']; ?></td>
                    <td><?php echo $service['hours']; ?> hours</td>
                    <td style="text-align: center">
                        <?php if(in_array($logged_user->user_role, [0,1]) || $service['user_id'] == $logged_user->ID){ ?>
                            <a href="<?php echo base_url() ?>services/update/<?php echo $service['id'] ?>">Edit</a>
                        <?php }?>
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" name="service[]" value="<?php echo $service['id']; ?>"  class="_service_"/>
                    </td>
                </tr>
            <?php endif; ?>
        <?php } ?>
        </tbody>
    </table>

    <input type="button" onclick="javascript: submit_form()" class="btn btn-lg btn-primary" value="Submit"/>
    </form>

    <?php foreach($services as $service){ ?>
    <?php if (!empty($service['name'])) : ?>
    <div id="desc-modal-<?php echo $service['id'] ?>" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $service['name'] ?></h4>
                </div>
                <div class="modal-body">
                    <!--SUBTASK TABLE-->
                    <h4>Description</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $service['description'] ?>
                        </div>
                    </div>
                    <hr/>
                    <h4>Notes</h4>
                    <div class="row">
                        <div class="col-md-12">
                             <?php echo $service['notes'] ?>
                        </div>
                    </div>
                    <hr/>
                    <h4>Attachment</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <?php $CI =& get_instance();?>
                            <?php echo (!empty($service['filename'] && file_exists(FCPATH."uploads/services/".$service['encrypt'])) ? "<a download href='".base_url() . $CI->settings->info->upload_path_relative ."/services/".$service['encrypt']."'>".$service['filename']."</a>" : 'N/A'); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
        <?php endif; ?>
    <?php } ?>
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
			<?php if($proposal_id != 0) { ?>
				self.close();

				//window.close();

				//window.top.close();
			<?php } ?>

            var st = $('#search_type').val();
            /*  var table = $('#services-table').DataTable({
             "dom" : "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
             "processing": false,
             "pagingType" : "full_numbers",
             "pageLength" : 15,
             "serverSide": true,
             "orderMulti": false,
             "order": [

             ],
             "columns": [
             null,
             null,
             null,
             { "orderable": false }
             ],
             "ajax": {
             type : 'GET',
             data : function ( d ) {
             d.search_type = $('#search_type').val();
             }
             },
             "drawCallback": function(settings, json) {
             $('[data-toggle="tooltip"]').tooltip();
             }
             });*/

            $('#form-search-input').on('keyup change', function(){
                var search = $(this).val();
                $(".services-tr").hide();
                $(".d-name").filter(function() {
                    return $(this).text().toLowerCase().indexOf(search) >= 0;
                }).closest('.services-tr').show();
            });

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

        function submit_form () {

            var countme = $('input[name="service[]"]:checked').length > 0;

            if (countme == 0) {
                alert('Please choose at least 1 service');
                return false;
            } else {
                $('#form_review').submit();
            }
        }
    </script>