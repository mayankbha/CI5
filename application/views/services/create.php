<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="white-area-content">

    <div class="db-header clearfix">
        <div class="page-header-title"> <span class="glyphicon glyphicon-th-list"></span> Add Service</div>
        <!--  <div class="db-header-extra"> <a href="--><?php //echo site_url("services/create") ?><!--" class="btn btn-primary btn-sm">--><?php //echo lang("ctn_1221") ?><!--</a>-->
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-body">
        <?php echo form_open(site_url("services/create_pro"), array("class" => "form-horizontal", "id" => "form-service", "enctype" => "multipart/form-data")) ?>
        <div class="form-group">
            <label for="p-in" class="col-md-2 label-heading">
                Service Name
            </label>
            <div class="col-md-8 ui-front">
                <input type="text" class="form-control" name="service_name" id="service_name" value="" placeholder="Service Name..." required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="recurring" class="col-md-2 label-heading">
                &nbsp;
            </label>
            <div class="col-md-8 ui-front">
                <input type="radio" name="recurring" value="0" checked="checked"> One Time &nbsp;
                <input type="radio" name="recurring" value="1"> Recurring
            </div>
        </div>
        <div class="form-group" id="interval-row">
            <label for="interval" class="col-md-2 label-heading" id="label-interval">
                How long is this service?
            </label>
            <div class="col-md-4 ui-front">
                <div class="row">
                    <div class="col-md-6">
                        <select name="interval" class="form-control">
                            <?php
                            for($x=1; $x<=24; $x++) {
                                echo '<option value='.$x.'>'.$x.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="interval_label" class="form-control" onchange="change_interval_label(this)">
                            <option value="weeks">weeks</option>
                            <option value="months">months</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="p-in" class="col-md-2 label-heading">
                Description
            </label>
            <div class="col-md-8 ui-front">
                <textarea class="form-control" name="service_description"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="p-in" class="col-md-2 label-heading">Tasks</label>
            <div class="col-md-8 ui-front">
                <!--DYNAMIC START-->
                <!--data-toggle="modal" data-target="#task-modal"-->
                <a href="javascript: void(0)" class="btn btn-primary" onclick="javascript: new_modal()" >New Task</a>
                <br/><br/>

                <table class="table table-bordered table-striped table-hover" id="tasks_table">
                    <tr id="copy-task-row">
                        <td class="text-center"><span class="glyphicon glyphicon-check sidebar-icon sidebar-icon-grey"></span></td>
                        <td>
                            <h4 class="text-primary" id="task-name">TASK NAME </h4>
                            <p>
                                <span id="task-price">$100 </span> | &nbsp;
                                <span id="task-hours">4 Hours to Complete</span> | &nbsp;
                                <!--<span id="task-note">Note</span> | &nbsp;
                                <span><b>Due:</b> <span  id="task-due"></span></span> | &nbsp;-->
                                <span><span  id="task-file"></span></span>
                            </p>
                        </td>
                        <td class="align-center">
                            <h4 class="text-primary" id="task-subtask-count"></h4>
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#task-modal-ctr">Manage</a>
                        </td>
                    </tr>
                </table>
                <!--<table class="table table-bordered table-striped table-hover" id="tab_logic">
                    <thead>
                    <tr >
                        <th class="text-center">Task Name</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Hours to complete</th>
                        <th class="text-center">Notes</th>
                        <th class="text-center">Due</th>
                        <th class="text-center">File</th>
                        <th class="text-center">Objectives</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id='task0'>
                        <td>
                            <input type="text" name='name[]' placeholder='Name' class="form-control task_name" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="text" name='price[]' placeholder='Price' class="form-control" id="ipp_0" autocomplete="off" onkeypress="return isNumber(event)"/>
                        </td>
                        <td>
                            <input type="text" name='hours[]' placeholder='Hours' class="form-control" id="iph_0" autocomplete="off" onkeypress="return isNumber(event)" />
                        </td>
                        <td>
                            <input type="text" name='description[]' placeholder='Notes' class="form-control task_description" autocomplete="off"/>
                        </td>
                        <td style="width: 80px;">
                            <select name="due_count[]" class="form-control">
                                <?php
                /*                                for($x=1; $x<=60; $x++) {
                                                    echo '<option value='.$x.'>'.$x.'</option>';
                                                }
                                                */?>
                            </select>
                        </td>
                        <td>
                            <input type="file" name='task_file[0]' class="form-control task_file"/>
                        </td>
                        <td>
                            <center>
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#subtask-modal-0">Manage <span id="subtask-0-count">(0)</span></a>
                            </center>
                        </td>
                    </tr>
                    </tbody>
                </table>-->
                <!--<div class="row">
                    <div class="col-md-12">
                        <a id="add_row" class="btn btn-default pull-left">Add Task</a>
                        <a id='delete_row' class="pull-right btn btn-default">Delete Row</a>
                    </div>
                </div>-->
                <!--DYNAMIC END-->
            </div>
        </div>
        <div class="form-group">
            <label for="p-in" class="col-md-2 label-heading">Pricing</label>
            <!--<div class="col-md-8 ui-front">
                <input type="radio" name="set_price" value="1" checked="checked"> Set Based on Tasks as Default &nbsp;
                <input type="radio" name="set_price" value="2"> Set Pricing as Default
            </div>-->

            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                    <span class="input-group-addon beautiful">
                        <input type="radio" name="set_price" value="1" checked="checked">
                    </span>
                        <input type="text" class="form-control fc-white" value="Set Based on Tasks as Default" readonly  onclick="javascript: set_price_mn(1)">
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="input-group">
                    <span class="input-group-addon beautiful">
                        <input type="radio" name="set_price" value="2">
                    </span>
                        <input type="text" class="form-control fc-white" value="Set Pricing as Default" readonly  onclick="javascript: set_price_mn(2)">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group" id="row_set_price">
            <label for="p-in" class="col-md-2 label-heading lhp">Price</label>
            <div class="input-group col-md-8">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="set_price_value" value="0" readonly class="form-control" onkeypress="return isNumber(event)"/>
            </div>
        </div>
        <div class="form-group">
            <label for="p-in" class="col-md-2 label-heading">
                Notes
            </label>
            <div class="col-md-8 ui-front">
                <textarea class="form-control" name="service_notes"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="p-in" class="col-md-2 label-heading">
                Attachment
            </label>
            <div class="col-md-8 ui-front">
                <input type="file" class="form-control" name="service_file" />
            </div>
        </div>
        <hr>

        <!--#######################################################################################-->
        <!--modal-->
        <!-- Modal -->
        <div id="modal-container"></div>

        <div id="modals"></div>

        <input type="hidden" id="ctr-modals" value="0">

        <div id="modal-repeat" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Repeat</h4>
                    </div>
                    <div class="modal-body">
                        <!--TABLE-->
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-hover" id="">
                                    <tr>
                                        <td>
                                            <label for="repeats" class=" label-heading">
                                                Repeats:
                                            </label>
                                        </td>
                                        <td>
                                            <select name="repeats" onclick="javascript: alter_repeat_value(this)">
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="repeat_every" class=" label-heading">
                                                Repeat every:
                                            </label>
                                        </td>
                                        <td>
                                            <select name="repeat_every">
                                                <?php
                                                for($x=1; $x<=30; $x++) {
                                                    echo '<option value='.$x.'>'.$x.'</option>';
                                                }
                                                ?>
                                            </select>&nbsp;
                                            <span class="repeats_label">weeks</span>
                                        </td>
                                    </tr>
                                    <tr class="tr_repeat_every">
                                        <td>
                                            <label for="repeat_every" class=" label-heading">
                                                Repeat on:
                                            </label>
                                        </td>
                                        <td>
                                            <div><span class="ep-rec-dow"><input id=":34.dow1" name="MO" type="checkbox" checked="checked" aria-label="Repeat on Monday" title="Monday">
                                                    <label for=":34.dow1" title="Monday">M</label>
                                                </span> &nbsp;
                                                <span class="ep-rec-dow"><input id=":34.dow2" name="TU" type="checkbox" aria-label="Repeat on Tuesday" title="Tuesday">
                                                    <label for=":34.dow2" title="Tuesday">T</label>
                                                </span>&nbsp;
                                                <span class="ep-rec-dow"><input id=":34.dow3" name="WE" type="checkbox" aria-label="Repeat on Wednesday" title="Wednesday">
                                                    <label for=":34.dow3" title="Wednesday">W</label>
                                                </span>&nbsp;
                                                <span class="ep-rec-dow"><input id=":34.dow4" name="TH" type="checkbox" aria-label="Repeat on Thursday" title="Thursday">
                                                    <label for=":34.dow4" title="Thursday">T</label>
                                                </span>&nbsp;
                                                <span class="ep-rec-dow"><input id=":34.dow5" name="FR" type="checkbox" aria-label="Repeat on Friday" title="Friday">
                                                    <label for=":34.dow5" title="Friday">F</label>
                                                </span>&nbsp;
                                                <span class="ep-rec-dow"><input id=":34.dow6" name="SA" type="checkbox" aria-label="Repeat on Saturday" title="Saturday">
                                                    <label for=":34.dow6" title="Saturday">S</label>
                                                </span>&nbsp;
                                                <span class="ep-rec-dow"><input id=":34.dow0" name="SU" type="checkbox" aria-label="Repeat on Sunday" title="Sunday">
                                                    <label for=":34.dow0" title="Sunday">S</label>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="starts_on" class=" label-heading">
                                                Starts on:
                                            </label>
                                        </td>
                                        <td>
                                            <input type="text" name="starts_on">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="ends_on" class=" label-heading">
                                                Ends
                                            </label>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <input type="radio"> Never
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <input type="radio"> After
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" class=" pull-right">
                                                </div>
                                                <div class="col-sm-2">
                                                    occurrences
                                                </div>
                                            </div>

                                            <div class="row mt-10">
                                                <div class="col-sm-3">
                                                    <input type="radio"> On
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" class=" pull-right">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="summary" class=" label-heading">
                                                Summary: <span class="repeats_label_2">Weekly</span>
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="" class="btn btn-default" data-dismiss="modal">OK</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="copy-task-modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Manage Task</h4>
                    </div>
                    <div class="modal-body">
                        <!--TABLE-->
                        <div class="row">
                            <div class="col-md-7">
                                <table class="table table-bordered table-striped table-hover" id="">
                                    <tr>
                                        <td>
                                            <label for="task_name" class=" label-heading">
                                                TASK NAME:
                                            </label>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control task_name" name="name[]" value="" required autocomplete="off">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="billing_role" class=" label-heading">
                                                BILLING ROLE:
                                            </label>
                                        </td>
                                        <td>
                                            <select name="billing_role[]" class="form-control" onchange="javascript: select_role(this)">
                                                <option value="0">Please select</option>
                                                <?php foreach($billing_roles->result() as $r) : ?>
                                                <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="price" class=" label-heading">
                                                PRICE:
                                            </label>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control prices" name="price[]" id="price" readonly placeholder="0.00" value="" required autocomplete="off" onkeypress="return isNumber(event)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="hours" class=" label-heading">
                                                HOURS TO COMPLETE:
                                            </label>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="hours[]" id="hours" value="" required placeholder="0" autocomplete="off" onkeypress="return isNumber(event)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="hours" class=" label-heading">
                                                ONE TIME/RECURRING
                                            </label>
                                        </td>
                                        <td>
                                            <input type="radio" onclick="javascript:set_task_recurring(0, 0)" class="t_recurring" name="task_recurring[ctr1]" value="0" checked="checked"> One Time &nbsp;
                                            <input type="radio" onclick="javascript:set_task_recurring(1, 0)" class="t_recurring" name="task_recurring[ctr2]" value="1"> Recurring <a id="manage_recurring" href="javascript:void(0)" onclick="javascript: repeat_modal()" class="btn btn-primary manage_recurring">Manage</a>
                                        </td>
                                    </tr>
                                    <tr class="row_due_date">
                                        <td>
                                            <label for="due_count" class=" label-heading">
                                                DUE DATE:
                                            </label>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select class="form-control" name="due_count[]">
                                                        <?php
                                                        for($x=1; $x<=24; $x++) {
                                                            echo '<option value='.$x.'>'.$x.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select name="task_interval_label" class="form-control" onchange="change_interval_label_2(this)">
                                                        <option value="weeks">weeks</option>
                                                        <option value="months">months</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="notes" class=" label-heading">
                                                NOTES:
                                            </label>
                                        </td>
                                        <td>
                                            <textarea class="form-control" name="notes[]"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="notes" class=" label-heading">
                                                ATTACHMENT:
                                            </label></td>
                                        <td>
                                            <input name='task_file[]' type='file' class='form-control task_file input-md'  />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-5 bg-info">
                                <h4 class="text-primary"><b>+ ADD SUBTASK</b></h4>
                                <table class="table table-subtask" id="">
                                    <tr class="bg-info">
                                        <td colspan="2">
                                            <b>Task Name:</b>
                                            <input type="text" name='subtask_name_ctr'  placeholder='Name' class="form-control" autocomplete="off"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>Billing Role:</b>
                                            <select name="subtask_billing_role_ctr" class="form-control" onchange="javascript: select_role_2(this)">
                                                <option value="0">Please select</option>
                                                <?php foreach($billing_roles->result() as $r) : ?>
                                                    <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Price:</b></td>
                                        <td><b>Hours to Complete:</b></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name='subtask_price_ctr'  readonly placeholder='0.00' class="form-control" autocomplete="off" onkeypress="return isNumber(event)"/></td>
                                        <td><input type="text" name='subtask_hours_ctr'  placeholder='0' class="form-control" autocomplete="off" onkeypress="return isNumber(event)"/></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b>Notes:</b>
                                            <textarea class="form-control" name="subtask_notes_ctr"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <button type="button" onclick="javacript: add_subtask_new()" class="btn btn-success">Add</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <br/><br/>
                        <div class="row mt-10">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-hover" id="subtask_table_ctr">
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Hours to complete</th>
                                        <th>Notes</th>
                                        <th></th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="javacript: add_task(ctrtask)" class="btn btn-default" data-dismiss="modal">Update</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="subtask-modal-0" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Manage Objectives</h4>
                    </div>
                    <div class="modal-body">
                        <!--SUBTASK TABLE-->
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="subtask-count-0" value="1">
                                <table class="table table-bordered table-striped table-hover" id="tab_logic">
                                    <thead>
                                    <tr >
                                        <th class="text-center">
                                            Task Name
                                        </th>
                                        <th class="text-center">
                                            Price
                                        </th>
                                        <th class="text-center">
                                            Hours to complete
                                        </th>
                                        <th class="text-center">
                                            Notes
                                        </th>
                                        <th>
                                            File
                                        </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="subtask-tbody-0">
                                    <tr id='addr0'>
                                        <td>
                                            <input type="text" name='subtask[0][name][]'  placeholder='Name' class="form-control" autocomplete="off"/>
                                        </td>
                                        <td>
                                            <input type="text" name='subtask[0][price][]' placeholder='Price' class="form-control input_price_0" autocomplete="off" onkeypress='return isNumber(event)'/>
                                        </td>
                                        <td>
                                            <input type="text" name='subtask[0][hours][]' placeholder='Hours' class="form-control input_hours_0" autocomplete="off" onkeypress='return isNumber(event)'/>
                                        </td>
                                        <td>
                                            <input type="text" name='subtask[0][notes][]'  placeholder='Notes' class="form-control" autocomplete="off"/>
                                        </td>
                                        <td>
                                            <input type="file" name='subtask[0][subtask_file][]' class="form-control">
                                        </td>
                                        <td>
                                            <a class="pull-right" href="javascript: void(0)" onclick="javascript: delete_sub_row(this)">[x]</a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <a id="add_row" onclick="javasccript: add_subtask_row(0);" class="btn btn-default pull-left">Add Subtask</a>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="javacript: add_subtask(0)" class="btn btn-default" data-dismiss="modal">Submit</button>
                    </div>
                </div>

            </div>
        </div>

        <input type="button" onclick="javascript: validate_service()" class="btn btn-primary form-control" value="<?php echo lang("ctn_1221") ?>">
        <?php echo form_close() ?>
    </div>
</div>


</div>

<script type="text/javascript">
    $(document).ready(function(){
        compute_price();

        var i=1;
        $("#add_row").click(function(){
            var this_html = "<td><input name='name["+i+"]' type='text' placeholder='Name' class='form-control task_name input-md'  /> </td><td><input  name='price["+i+"]' type='text' placeholder='Price'  class='form-control input-md' id='ipp_"+i+"' onkeypress='return isNumber(event)'></td><td><input  name='hours["+i+"]' type='text' placeholder='Hours'  class='form-control input-md' id='iph_"+i+"' onkeypress='return isNumber(event)'></td>";

            this_html += "<td><input name='description["+i+"]' type='text' placeholder='Notes' class='form-control task_description input-md'  /></td>";
            this_html += "<td><select name='due_count["+i+"]' class='form-control'>";
            for (due = 1; due <= 60; due++) {
                this_html += "<option value="+due+">"+due+"</option>";
            }
            this_html += "</select></td>";
            this_html += "<td><input name='task_file["+i+"]' type='file' class='form-control task_file input-md'  /></td>";

            this_html += '<td>'+
                '<center>'+
                '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#subtask-modal-'+i+'">Manage <span id="subtask-'+i+'-count">(0)</span></a>'+
                '</center>'+
                '</td>';

            $('#tab_logic').append('<tr id="addr'+(i)+'"></tr>');

            var this_html_modal ="";
            this_html_modal += "<div id=\"subtask-modal-"+i+"\" class=\"modal fade\" role=\"dialog\">";
            this_html_modal += "<div class=\"modal-dialog modal-lg\">";
            this_html_modal += "<!-- Modal content-->";
            this_html_modal += "<div class=\"modal-content\">";
            this_html_modal += "<div class=\"modal-header\">";
            this_html_modal += "<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;<\/button>";
            this_html_modal += "<h4 class=\"modal-title\">Modal Header<\/h4>";
            this_html_modal += "<\/div>";
            this_html_modal += "<div class=\"modal-body\">";
            this_html_modal += "<!--SUBTASK TABLE-->";
            this_html_modal += "<div class=\"row\">";
            this_html_modal += "<div class=\"col-md-12\">";
            this_html_modal += "<input type=\"hidden\" id=\"subtask-count-"+i+"\" value=\"1\">";
            this_html_modal += "<table class=\"table table-bordered table-striped table-hover\" id=\"tab_logic\">";
            this_html_modal += "<thead>";
            this_html_modal += "<tr >";
            this_html_modal += "<th class=\"text-center\">";
            this_html_modal += "Task Name";
            this_html_modal += "<\/th>";
            this_html_modal += "<th class=\"text-center\">";
            this_html_modal += "Price";
            this_html_modal += "<\/th>";
            this_html_modal += "<th class=\"text-center\">";
            this_html_modal += "Hours to complete";
            this_html_modal += "<\/th>";
            this_html_modal += "<th class=\"text-center\">";
            this_html_modal += "Notes";
            this_html_modal += "<\/th>";
            this_html_modal += "<th class=\"text-center\">";
            this_html_modal += "File";
            this_html_modal += "<\/th>";
            this_html_modal += "<th>";
            this_html_modal += "";
            this_html_modal += "<\/th>";
            this_html_modal += "<\/tr>";
            this_html_modal += "<\/thead>";
            this_html_modal += "<tbody id=\"subtask-tbody-"+i+"\">";
            this_html_modal += "<tr id='addr0'>";
            this_html_modal += "<td>";
            this_html_modal += "<input type=\"text\" name='subtask["+i+"][name][]'  placeholder='Name' class=\"form-control\"\ autocomplete=\"off\"/>";
            this_html_modal += "<\/td>";
            this_html_modal += "<td>";
            this_html_modal += "<input type=\"text\" name='subtask["+i+"][price][]' placeholder='Price' class='form-control input_price_"+i+"' autocomplete=\"off\" onkeypress='return isNumber(event)'/>";
            this_html_modal += "<\/td>";
            this_html_modal += "<td>";
            this_html_modal += "<input type=\"text\" name='subtask["+i+"][hours][]' placeholder='Hours' class='form-control input_hours_"+i+"' autocomplete=\"off\" onkeypress='return isNumber(event)'/>";
            this_html_modal += "<\/td>";
            this_html_modal += "<td>";
            this_html_modal += "<input type=\"text\" name='subtask["+i+"][notes][]' placeholder='Hours' class='form-control input_notes_"+i+"' autocomplete=\"off\"/>";
            this_html_modal += "<\/td>";
            this_html_modal += "<td>";
            this_html_modal += "<input type=\"file\" name='subtask["+i+"][subtask_file][]' class='form-control input_subtaskfile_"+i+"' />";
            this_html_modal += "<\/td>";
            this_html_modal += "<td>";
            this_html_modal += "<a class='pull-right' href='javascript: void(0)' onclick='javascript: delete_sub_row(this)'>[x]</a>";
            this_html_modal += "<\/td>";
            this_html_modal += "<\/tr>";
            this_html_modal += "<tr id='addr'"+i+"><\/tr>";
            this_html_modal += "<\/tbody>";
            this_html_modal += "<\/table>";
            this_html_modal += "<a id=\"add_row\"onclick=\"javasccript: add_subtask_row("+i+");\" class=\"btn btn-default pull-left\">Add Objective<\/a>";
            //this_html_modal += "<a id='"+i+"' class=\"pull-right btn btn-default delete_row_k\">Delete Row<\/a>";
            this_html_modal += "<\/div>";
            this_html_modal += "<\/div>";
            this_html_modal += "";
            this_html_modal += "";
            this_html_modal += "<\/div>";
            this_html_modal += "<div class=\"modal-footer\">";
            this_html_modal += "<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" onclick=\"javacript: add_subtask("+i+")\">Submit<\/button>";
            this_html_modal += "<\/div>";
            this_html_modal += "<\/div>";
            this_html_modal += "";
            this_html_modal += "<\/div>";
            this_html_modal += "<\/div>";
            $('#modal-container').append(this_html_modal);


            $('#addr'+i).html(this_html);

            i++;
        });
        $("#delete_row").click(function(){
            if(i>1){
                $("#addr"+(i-1)).html('');
                i--;
            }
        });

        $("#delete_row_k").click(function(){
            if(i>1){
                $("#addr"+(i-1)).html('');
                i--;
            }
        });

        $('input:radio[name=recurring]').click(function(){
            if (this.value == 0) {
                $('#label-interval').text('How long is this service?');
            } else {
                $('#label-interval').text('Repeats every');
            }
        });

        $('input:radio[name=set_price]').click(function(){
            if (this.value == 2) {
                $('input[name="set_price_value"]').removeAttr('readonly');
            } else {
                $('input[name="set_price_value"]').attr('readonly', 'readonly');
                compute_price();
            }
        });
    });


    function new_modal () {
        var ctr = $('#ctr-modals').val();

        var html_copy_modal = $('#copy-task-modal').html();

        html_copy_modal = html_copy_modal.replace('ctrtask', ctr);
        html_copy_modal = html_copy_modal.replace('name[]', 'name['+ctr+']');
        html_copy_modal = html_copy_modal.replace('billing_role[]', 'billing_role['+ctr+']');
        html_copy_modal = html_copy_modal.replace('price[]', 'price['+ctr+']');
        html_copy_modal = html_copy_modal.replace('hours[]', 'hours['+ctr+']');
        html_copy_modal = html_copy_modal.replace('task_recurring[ctr1]', 'recurring['+ctr+']');
        html_copy_modal = html_copy_modal.replace('task_recurring[ctr2]', 'recurring['+ctr+']');
        html_copy_modal = html_copy_modal.replace('manage_recurring', 'manage_recurring_'+ctr);
        html_copy_modal = html_copy_modal.replace('set_task_recurring(0, 0)', 'set_task_recurring(0, '+ctr+')');
        html_copy_modal = html_copy_modal.replace('set_task_recurring(1, 0)', 'set_task_recurring(1, '+ctr+')');
        html_copy_modal = html_copy_modal.replace('select_role(this)', 'select_role(this, '+ctr+')');
        html_copy_modal = html_copy_modal.replace('select_role_2(this)', 'select_role_2(this, '+ctr+')');
        html_copy_modal = html_copy_modal.replace('due_count[]', 'due_count['+ctr+']');
        html_copy_modal = html_copy_modal.replace('change_interval_label_2(this)', 'change_interval_label_2(this, '+ctr+')');
        html_copy_modal = html_copy_modal.replace('notes[]', 'notes['+ctr+']');
        html_copy_modal = html_copy_modal.replace('task_file[]', 'task_file['+ctr+']');
        html_copy_modal = html_copy_modal.replace('task-modal-ctr', 'task-modal-'+ctr);
        html_copy_modal = html_copy_modal.replace('add_subtask_new()', 'add_subtask_new('+ctr+')');
        html_copy_modal = html_copy_modal.replace('subtask_table_ctr', 'subtask_table_'+ctr+'');
        html_copy_modal = html_copy_modal.replace('subtask_name_ctr', 'subtask_name_'+ctr+'');
        html_copy_modal = html_copy_modal.replace('subtask_price_ctr', 'subtask_price_'+ctr+'');
        html_copy_modal = html_copy_modal.replace('subtask_hours_ctr', 'subtask_hours_'+ctr+'');
        html_copy_modal = html_copy_modal.replace('subtask_notes_ctr', 'subtask_notes_'+ctr+'');
        html_copy_modal = html_copy_modal.replace('subtask[ctr]subtask_file][]', 'subtask['+ctr+']subtask_file][]');
        html_copy_modal = html_copy_modal.replace('subtask_billing_role_ctr', 'subtask_billing_role_'+ctr+'');

        $('#modals').append('<div id="task-modal-'+ctr+'" class="modal fade" role="dialog">' + html_copy_modal + '</div>');

        $('#task-modal-'+ctr).modal('show');

        $('#ctr-modals').val(parseInt(ctr) + 1);
    }

    function add_task (ctr) {
        var html_copy_task = '<tr id="task-row-'+ctr+'">';
        html_copy_task += $('#copy-task-row').html();
        html_copy_task += '</tr>';

        html_copy_task = html_copy_task.replace('task-name', 'task-name-'+ctr);
        html_copy_task = html_copy_task.replace('task-price', 'task-price-'+ctr);
        html_copy_task = html_copy_task.replace('task-hours', 'task-hours-'+ctr);
        html_copy_task = html_copy_task.replace('task-note', 'task-note-'+ctr);
        html_copy_task = html_copy_task.replace('task-file', 'task-file-'+ctr);
        html_copy_task = html_copy_task.replace('task-due', 'task-due-'+ctr);
        html_copy_task = html_copy_task.replace('task-due-days', 'task-due-days-'+ctr);
        html_copy_task = html_copy_task.replace('task-subtask-count', 'task-subtask-count-'+ctr);
        html_copy_task = html_copy_task.replace('task-modal-ctr', 'task-modal-'+ctr);

        if ($("#task-row-"+ctr).html() == ''  || $("#task-row-"+ctr).html() == undefined) {
            $('#tasks_table').append(html_copy_task);
        }

        $('#task-name-'+ctr).html('<b>TASK NAME: </b>'+$('input[name="name['+ctr+']"').val());
        $('#task-price-'+ctr).html('$'+$('input[name="price['+ctr+']"').val());
        $('#task-hours-'+ctr).html($('input[name="hours['+ctr+']"').val() + ' Hours to Complete');

        var filecount = 0;
        if ($('input[name="task_file['+ctr+']"]').val() != '') {
            filecount = 1;
        }
        $('#task-file-'+ctr).html(filecount + ' File');
        //$('#task-due-'+ctr).html($('select[name="due_count['+ctr+']"').val());
        //$('#task-due-days-'+ctr).html($('select[name="due_count['+ctr+']"').val() + ' days');
        var subtaskcount = parseInt(($('#subtask_table_'+ctr+'>tbody>tr').length));

        if (subtaskcount >= 2) {
            $('#task-subtask-count-'+ctr).html(subtaskcount-1+'<br/><span style="font-size: 10px">subtask</span>');
        } else {
            $('#task-subtask-count-'+ctr).html('');
        }
        $('#task-note-'+ctr).html($('textarea[name="notes['+ctr+']"').val());

        compute_price();
    }

    function add_subtask_new (ctr) {
        var subtask_name = $("input[name='subtask_name_"+ctr+"']").val();
        var subtask_price = $("input[name='subtask_price_"+ctr+"']").val();
        var subtask_hours = $("input[name='subtask_hours_"+ctr+"']").val();
        var subtask_notes = $("textarea[name='subtask_notes_"+ctr+"']").val();
        var subtask_role= $("select[name='subtask_billing_role_"+ctr+"']").val();
        //var subtask_file= $("input[name='subtask["+ctr+"]subtask_file][]']").val();

        var hidden_fields = "<input type='hidden' name='subtask["+ctr+"][name][]' class='form-control' value='"+subtask_name+"'/>";
        hidden_fields += "<input type='hidden' name='subtask["+ctr+"][price][]' class='form-control' value='"+subtask_price+"'/>";
        hidden_fields += "<input type='hidden' name='subtask["+ctr+"][hours][]' class='form-control' value='"+subtask_hours+"'/>";
        hidden_fields += "<input type='hidden' name='subtask["+ctr+"][notes][]' class='form-control' value='"+subtask_notes+"'/>";
        hidden_fields += "<input type='hidden' name='subtask["+ctr+"][billing_role][]' class='form-control' value='"+subtask_role+"'/>";
        //hidden_fields += "<input type='file' style='opacity:0' name='subtask["+ctr+"][subtask_file][]' class='form-control' value='"+subtask_file+"'/>";

        var subtask_new = '<tr>';
        subtask_new += '<td>'+subtask_name+'</td>';
        subtask_new += '<td>'+subtask_price+'</td>';
        subtask_new += '<td>'+subtask_hours+'</td>';
        subtask_new += '<td>'+subtask_notes+hidden_fields+'</td>';
        subtask_new += '<td><a href="javascript:void(0)" onclick="javascript: delete_subtask(this)">Delete</a></td>';
        //subtask_new += '<td>'+hidden_fields+'</td>';

        $('#subtask_table_'+ctr).append(subtask_new);
    }

    function delete_subtask(_this_) {
        $(_this_).closest('tr').remove();
    }

    function select_role (_this_, ctr) {
        var id = $(_this_).val();
        $.ajax({
            url: global_base_url + "team/get_billing_role",
            type: "GET",
            data: {
                id: id
            },
            dataType : 'html',
            success: function(msg) {
                $('input[name="price['+ctr+']"]').val(msg);
            }
        })
    }

    function select_role_2 (_this_, ctr) {
        var id = $(_this_).val();
        $.ajax({
            url: global_base_url + "team/get_billing_role",
            type: "GET",
            data: {
                id: id
            },
            dataType : 'html',
            success: function(msg) {
                $('input[name="subtask_price_'+ctr+'"]').val(msg);
            }
        })
    }

    function compute_price () {
        var price = 0;
        $('.prices').each(function() {
            if (isNumeric(this.value) && this.value != 0) {
                price += parseInt(this.value);
            }
        });

        $('input[name="set_price_value"]').val(price);
    }

    function change_interval_label (_this_) {
        var options = '';
        if($(_this_).val() == 'months') {
            for(var x=1; x<=6; x++) {
                options += '<option value='+x+'>'+x+'</option>';
            }
        } else {
            for(var x=1; x<=24; x++) {
                options += '<option value='+x+'>'+x+'</option>';
            }
        }

        $('select[name="interval"]').html('');
        $('select[name="interval"]').html(options);
    }

    function change_interval_label_2 (_this_, taskid) {
        var options = '';
        if($(_this_).val() == 'months') {
            for(var x=1; x<=6; x++) {
                options += '<option value='+x+'>'+x+'</option>';
            }
        } else {
            for(var x=1; x<=24; x++) {
                options += '<option value='+x+'>'+x+'</option>';
            }
        }

        $('select[name="due_count['+taskid+']"').html('');
        $('select[name="due_count['+taskid+']"').html(options);
    }

    function set_task_recurring (r, taskid) {
        if (r == 1) {
            $('#manage_recurring_'+taskid).show();
            $('.row_due_date').hide();
        } else {
            $('#manage_recurring_'+taskid).hide();
            $('.row_due_date').show();
        }
    }

    function repeat_modal () {
        $('#modal-repeat').modal('show');
    }

    function alter_repeat_value (_this_) {
        if ($(_this_).val() == 'weekly') {
            $('.repeats_label').text(' weeks');
            $('.repeats_label_2').text(' Weekly');
            $('.tr_repeat_every').show();
        } else {
            $('.tr_repeat_every').hide();
            $('.repeats_label').text(' months');
            $('.repeats_label_2').text(' Monthly');
        }
    }

    function set_price_mn (set_val) {
        if (set_val == 1) {
            $('#set_price_2').removeAttr('checked');

            $('input[type="radio"][name="set_price"][value="'+set_val+'"]').attr('checked','checked');
            $('input[type="radio"][name="set_price"][value="'+set_val+'"]').click();
            $('#set_price_1').attr('checked',true);
        } else {
            $('#set_price_1').removeAttr('checked');

            $('input[type="radio"][name="set_price"][value="'+set_val+'"]').attr('checked','checked');
            $('input[type="radio"][name="set_price"][value="'+set_val+'"]').click();
            $('#set_price_2').attr('checked',true);
        }
    }

    function add_subtask_row(subtask_id){
        var this_html = "<tr id='addrk"+subtask_id+"'><td><input name='subtask["+subtask_id+"][name][]' type='text' placeholder='Name' class='form-control input-md' autocomplete='off' /> </td><td><input  name='subtask["+subtask_id+"][price][]' type='text' placeholder='Price'  class='form-control input-md input_price_"+subtask_id+"' autocomplete='off' onkeypress='return isNumber(event)'></td><td><input  name='subtask["+subtask_id+"][hours][]' type='text' placeholder='Hours'  class='form-control input-md input_hours_"+subtask_id+"' autocomplete='off' onkeypress='return isNumber(event)'></td><td><input name='subtask["+subtask_id+"][notes][]' type='text' placeholder='Notes' class='form-control input-md' autocomplete='off' /> </td><td><input name='subtask["+subtask_id+"][subtask_file][]' type='file' class='form-control input-md' /> </td><td><a class='pull-right' href='javascript: void(0)' onclick='javascript: delete_sub_row(this)'>[x]</a></td></tr>";

        var subtask_count = $('#subtask-count-'+subtask_id).val();
        $('#subtask-count-'+subtask_id).val(parseInt(subtask_count)+1);

        $('#subtask-'+subtask_id+'-count').html('('+$('#subtask-count-'+subtask_id).val()+')');
        $('#subtask-tbody-'+subtask_id).append(this_html);
    }

    function delete_sub_row (_this_) {
        $(_this_).parent().parent().remove();
    }

    function add_subtask (subtask_id) {
        var totalSubPrice  = 0;
        var totalSubHours  = 0;
        var countme  = 0;
        $('.input_price_'+subtask_id).each(function() {
            if (isNumeric(this.value) && this.value != 0) {
                totalSubPrice += parseInt(this.value);
                countme++;
            }
        });

        $('.input_hours_'+subtask_id).each(function() {
            if (isNumeric(this.value) && this.value != 0) {
                totalSubHours += parseInt(this.value);
            }
        });

        if (totalSubPrice != 0) {
            $('#ipp_'+subtask_id).val(totalSubPrice);
        }

        if (totalSubHours != 0) {
            $('#iph_'+subtask_id).val(totalSubHours);
        }

        $('#subtask-'+subtask_id+'-count').html('('+countme+')');
    }

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function validate_service () {
        var service_name = $('#service_name').val();

        /*check first if service name is not empty*/
        if ($.trim(service_name) != '') {
            var valid_task_count = 0;
            $('.task_name').each(function() {
                if ($.trim(this.value) != '')
                    valid_task_count += 1;
            });


            if (valid_task_count == 0) {
                alert('You should have at least 1 task for each service');
                return false;
            } else {
                if ($('#ipp_0').val() == '' || $('#ipp_0').val() == '0') {
                    alert('Price should not be empty or 0');
                    return false;
                }

                if ($('#iph_0').val() == '' || $('#iph_0').val() == '0') {
                    alert('Hours should not be empty or 0');
                    return false
                }
                $('#form-service').submit();
            }
        } else {
            alert('Service name is empty');
            return false;
        }
    }


    $(document).ready(function() {
//CKEDITOR.replace('note-area', { height: '100'});
//CKEDITOR.replace('invoice-area', { height: '100'});
    });
</script>

<style type="text/css">
    .no-url {
        cursor: hand;
        color: black;
    }

    .no-url:hover{
        color: black;
        text-decoration: none;
    }

    #copy-task-row {
        display: none;
    }

    #row_set_price {
        /*display: none;*/

    }

    .lhp{
        margin-right: 14px;
    }

    .table-subtask, .table-subtask > tbody > tr > td{
        border: 0px !important;
    }

    .input-group-addon.beautiful input[type="checkbox"],
    .input-group-addon.beautiful input[type="radio"] {
        /*display: none;*/
    }

    .fc-white {
        background-color: white !important;
        height: 47px;
        cursor: pointer;
    }



    .input-group {
        /*height: 47px;*/
    }

    .inner-addon {
        position: relative;
    }

    /* style icon */
    .inner-addon .glyphicon {
        position: absolute;
        padding: 10px;
        pointer-events: none;
    }

    /* align icon */
    .left-addon .glyphicon  { left:  0px;}
    .right-addon .glyphicon { right: 0px;}

    /* add padding  */
    .left-addon input  { padding-left:  30px; }
    .right-addon input { padding-right: 30px; }

    .ig2 {
        /*height: 34px !important;*/
    }

    .manage_recurring {
        display: none;
    }


    span.glyphicon-check {
        font-size: 1.8em;
    }

    .mt-10 {
        margin-top: 10px;
    }
</style>



