<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Solved';
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
                                        <?php } ?>
                                        <th>ACTION TAKEN</th>
                                        <th>SOLVED DATE</th>
                                        <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                                            <th>ACRHIVE</th>
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
  
<script>
    $(function () {
        $(document).on('click', '.status', function(){
            var id = $(this).attr('id');
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
                    var btn_action = 'Reports_status';
                    var status = 'Archived';
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data: {id:id, status:status, btn_action:btn_action} ,
                        dataType:"json",
                        success:function(data)
                        {
                            if (data.status == true)
                            {
                                dataTable.ajax.reload();
                            }
                            else 
                            {
                                toastr.info(data.message);
                            }
                        },error:function()
                        {
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
                url:"fetch/reports_filed.php?status=Solved",
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