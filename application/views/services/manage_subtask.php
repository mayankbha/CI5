<table class="table table-bordered table-striped table-hover" id="tab_logic_subtask">
    <thead>
    <tr >
        <th class="text-center">Task Name</th>
        <th class="text-center">Price</th>
        <th class="text-center">Hours to complete</th>
    </tr>
    </thead>
    <tbody>
    <tr id='task0'>
        <td>
            <input type="text" name='name0'  placeholder='Name' class="form-control"/>
        </td>
        <td>
            <input type="text" name='mail0' placeholder='100' class="form-control"/>
        </td>
        <td>
            <input type="text" name='mobile0' placeholder='1.00' class="form-control"/>
        </td>
    </tr>
    <tr id='subtask_addr1'></tr>
    </tbody>
</table>
<div class="row">
    <div class="col-md-12">
        <a id="add_row_subtask" class="btn btn-default pull-left">Add SubTask</a>
        <a id='delete_row_subtask' class="pull-right btn btn-default">Delete SubTask</a>
    </div>

</div>
<script>
    $(document).ready(function(){
        var i=1;
        $("#add_row_subtask").click(function(){

            var this_html = "<td><input name='name"+i+"' type='text' placeholder='Name' class='form-control input-md'  /> </td><td><input  name='mail"+i+"' type='text' placeholder='100'  class='form-control input-md'></td><td><input  name='mobile"+i+"' type='text' placeholder='1.00'  class='form-control input-md'></td>";


            $('#subtask_addr'+i).html(this_html);

            $('#tab_logic_subtask').append('<tr id="subtask_addr'+(i+1)+'"></tr>');
            i++;
        });
        $("#delete_row_subtask").click(function(){
            if(i>1){
                $("#subtask_addr"+(i-1)).html('');
                i--;
            }
        });

    });
</script>