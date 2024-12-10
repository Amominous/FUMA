<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Ongoing';
include('header.php');
include('sidebar.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-success">
                        <div class="card-body">
                            <table id="datatables" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th>ROOM</th>
                                        <th>SECTION</th>
                                        <th>SUBMITTED BY</th>
                                        <th>SUBMITTED DATE</th>
                                        <th>PC #</th>
                                        <th>CATEGORY</th>
                                        <th>PC STATUS</th>
                                        <th>ISSUE</th>
                                        <th>VERIFIED DATE</th>
                                        <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                                            <th>VERIFIED BY</th>
                                            <th>SOLVE</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
include('footer.php');
?>

<div id="addModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <form method="post" id="forms">
            <div class="modal-content" >
                <div class="modal-header bg-success">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <label>Action Taken</label>
                            <textarea name="action_taken" id="action_taken" class="form-control " required ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-success ">
                    <input type="hidden" name="id" id="id"/>
                    <input type="hidden" name="status" id="status"/>
                    <input type="hidden" name="btn_action" id="btn_action"/>
                    <button type="submit" class="btn btn-default pl-3 pr-3 " name="action" id="action" ><i class='fas fa-plus-circle'></i> Add</button>
                    <button type="button" class="btn btn-default pl-3 pr-3 " data-dismiss="modal" ><i class='fas fa-times-circle text-danger'></i> Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
  
<script>
    $(function () {
    
        $(document).on('click', '.status', function(){
            var id = $(this).attr('id');
            $('#forms')[0].reset();
            $('#id').val(id);
            $('#status').val('Solved');
            $('#addModal').modal('show');
            $('.modal-title').html("<i class='fas fa-check-circle'></i> Solve");
            $('#action').html("<i class='fas fa-save'></i> Submit");
            $('#action').val("Reports_status");
            $('#btn_action').val('Reports_status');
        });
    
        $(document).on('submit','#forms', function(event){
            event.preventDefault();
            Swal.fire({
                icon: 'question',
                title: 'Are you sure to solve this report?',
                showCancelButton: true,
                showDenyButton: false,
                confirmButtonText: '<i class="fa fa-check-circle"></i> Yes',
                cancelButtonText: `<i class="fa fa-times-circle"></i> No`,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
            }).then((result) => {
                if (result.isConfirmed) 
                {
                    $('#action').attr('disabled','disabled');
                    var form_data = $(this).serialize();
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data:form_data,
                        dataType:"json",
                        success:function(data)
                        {
                            $('#action').attr('disabled', false);
                            if (data.status == true)
                            {
                                $('#forms')[0].reset();
                                $('#addModal').modal('hide');
                                dataTable.ajax.reload();
                            }
                            else 
                            {
                                toastr.info(data.message);
                            }
                        },error:function()
                        {
                            $('#action').attr('disabled', false);
                            toastr.info('Something went wrong.');
                        }
                    })
                } 
                else if (result.isDenied) { }
            })
        });

        var dataTable = $("#datatables").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "processing":true,
            "serverSide":true,
            "ordering": false,
            "order":[],
            "ajax":{
                url:"fetch/reports_filed.php?status=Ongoing",
                type:"POST"
            },
            "columnDefs":[
                {
                "targets":[0],
                "orderable":false,
                },
            ],
            "pageLength": 10, 
        });

    });
</script>

</body>
</html>