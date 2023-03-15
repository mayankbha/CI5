<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_887") ?></div>
    <div class="db-header-extra form-inline"> 
    <div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_844") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("team") ?>"><?php echo lang("ctn_845") ?></a></li>
    <?php foreach($projects->result() as $r) : ?>
      <?php if($r->ID == $projectid) $proj = $r; ?>
      <li><a href="<?php echo site_url("team/".$page."/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>
</div>

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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok no-display" id="user-exact"></span> <?php echo lang("ctn_357") ?></a></li>
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok no-display" id="role-exact"></span> <?php echo lang("ctn_360") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_910") ?></button>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addNewModal"><?php echo 'Add New User' ?></button>
</div>
</div>

<?php if(isset($projectid) && $projectid > 0 && isset($proj)) : ?>
  <?php $project = $proj; ?>
<p><?php echo lang("ctn_911") ?> <strong><?php echo $project->name ?></strong> <?php if($this->user->info->active_projectid == $projectid) : ?> (<?php echo lang("ctn_912") ?>) <?php endif; ?></p>
<?php endif; ?>

<div class="table-responsive">
<table id="team-table" class="table table-bordered table-striped table-hover">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_357") ?></td><td><?php echo lang("ctn_360") ?></td><td><?php echo lang("ctn_825") ?></td><td><?php echo lang("ctn_913") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>

</div>


<script type="text/javascript">
    function checkUsername() {
        var username = $('#username').val();
        if(username.length > 0) {
            $.ajax({
                url: global_base_url + "register/check_username",
                type: "get",
                data: {
                    "username" : username
                },
                success: function(msg) {
                    $('#username_check').html(msg);
                }
            });
        } else {
            $('#username_check').html('');
        }
    }

    $(document).ready(function() {
        $('#username').change(function() {
            checkUsername();
        });
    });
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#team-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
          [2, "asc" ]
        ],
        "columns": [
        null,
        null,
        null,
        null,
        { "orderable": false },
    ],
        "ajax": {
            url : "<?php echo site_url("team/team_page/" . $page . "/" . $projectid) ?>",
            type : 'GET',
            data : function ( d ) {
                d.search_type = $('#search_type').val();
            }
        },
        "drawCallback": function(settings, json) {
        $('[data-toggle="tooltip"]').tooltip();
      }
    });
    $('#form-search-input').on('keyup change', function () {
    table.search(this.value).draw();
});

} );
function change_search(search) 
    {
      var options = [
        "search-like", 
        "search-exact",
        "user-exact",
        "role-exact",
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