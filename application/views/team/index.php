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

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_910") ?></h4>
      </div>
      <div class="modal-body ui-front">
         <?php echo form_open(site_url("team/add_team_member"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_25") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="username" value="" id="username-search">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control">
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_360") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="roleid" class="form-control">
                        <?php foreach($roles->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_910") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>


<!--Add new user-->
<div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo 'Add New User'; ?></h4>
      </div>
      <div class="modal-body ui-front">
          <?php if(!empty($fail)) : ?>
              <div class="alert alert-danger"><?php echo $fail ?></div>
          <?php endif; ?>

          <?php echo form_open(site_url("register"), array("class" => "form-horizontal")) ?>
          <input type="hidden" name="bypass_login_check" value="1"/>
          <input type="hidden" name="parent_user_id" value="<?php echo $this->user->info->ID; ?>"/>
          <div class="form-group">

              <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_214") ?></label>
              <div class="col-md-9">
                  <input type="email" class="form-control" id="email-in" name="email" value="<?php if(isset($email)) echo $email; ?>">
              </div>
          </div>

          <div class="form-group">

              <label for="username-in" class="col-md-3 label-heading"><?php echo lang("ctn_215") ?></label>
              <div class="col-md-6">
                  <input type="text" class="form-control" id="username" name="username" value="<?php if(isset($username)) echo $username; ?>">
                  <div id="username_check"></div>
              </div>
              <div class="col-md-3">
                  <input type="button" class="btn btn-default" value="<?php echo lang("ctn_210") ?>" onclick="checkUsername()" />
              </div>
          </div>

          <div class="form-group">

              <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_216") ?></label>
              <div class="col-md-9">
                  <input type="password" class="form-control" id="password-in" name="password" value="">
              </div>
          </div>

          <div class="form-group">

              <label for="cpassword-in" class="col-md-3 label-heading"><?php echo lang("ctn_217") ?></label>
              <div class="col-md-9">
                  <input type="password" class="form-control" id="cpassword-in" name="password2" value="">
              </div>
          </div>

          <div class="form-group">

              <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_218") ?></label>
              <div class="col-md-9">
                  <input type="text" class="form-control" id="name-in" name="first_name" value="<?php if(isset($first_name)) echo $first_name ?>">
              </div>
          </div>
          <div class="form-group">

              <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_219") ?></label>
              <div class="col-md-9">
                  <input type="text" class="form-control" id="name-in" name="last_name" value="<?php if(isset($last_name)) echo $last_name ?>">
              </div>
          </div>

          <p>* = <?php echo lang("ctn_957") ?></p>

          <?php if(!$this->settings->info->disable_captcha) : ?>
              <div class="form-group">

                  <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_220") ?></label>
                  <div class="col-md-9">
                      <p><?php echo $cap['image'] ?></p>
                      <input type="text" class="form-control" id="captcha-in" name="captcha" placeholder="<?php echo lang("ctn_306") ?>" value="">
                  </div>
              </div>
          <?php endif; ?>
          <?php if($this->settings->info->google_recaptcha) : ?>
              <div class="form-group">

                  <label for="name-in" class="col-md-3 label-heading"><?php echo lang("ctn_220") ?></label>
                  <div class="col-md-9">
                      <div class="g-recaptcha" data-sitekey="<?php echo $this->settings->info->google_recaptcha_key ?>"></div>
                  </div>
              </div>
          <?php endif ?>


<!--          <input type="submit" name="s" class="btn btn-primary form-control" value="--><?php //echo lang("ctn_221") ?><!--" />-->




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" name="s" class="btn btn-primary" value="<?php echo lang("ctn_910") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
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