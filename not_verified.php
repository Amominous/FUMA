<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Not Verified';
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
                                        <th>REMARKS</th>
                                        <th>DATE</th>
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

        var dataTable = $("#datatables").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "processing":true,
            "serverSide":true,
            "ordering": false,
            "order":[],
            "ajax":{
                url:"fetch/reports_filed.php?status=Not Verified",
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