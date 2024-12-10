<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Archived';
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
                        <div class="card-header">
                            <form method="post" id="forms">
                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <div class="input-group date" id="firsts" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" data-target="#firsts" 
                                            name="first" id="first" required/>
                                            <div class="input-group-append" data-target="#firsts" data-toggle="datetimepicker" >
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="input-group date" id="seconds" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" data-target="#seconds" 
                                            name="second" id="second" required />
                                            <div class="input-group-append" data-target="#seconds" data-toggle="datetimepicker" >
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <button type="submit" class="btn btn-default pl-3 pr-3 " ><i class='fas fa-search'></i> Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
        $('#firsts').datetimepicker({
            format: 'MM-DD-YYYY',
        });
        $('#seconds').datetimepicker({
            format: 'MM-DD-YYYY',
        });
        $(document).on('submit','#forms', function(event){
            event.preventDefault();
            // $('#btn_search').attr('disabled','disabled');
            var first = $('#first').val();
            var second = $('#second').val();
            
            $('#datatables').DataTable().destroy();
            dataTable = $("#datatables").DataTable({
                "initComplete": function () { 
                    $("#datatables").DataTable().buttons().container().appendTo( $('.col-sm-12:eq(0)', $("#datatables").DataTable().table().container() ) );   
                },
                "buttons": [ 
                    {
                        extend: 'print',
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    },
                    // {
                    //     extend: 'pdfHtml5',
                    //     // exportOptions: {
                    //     //     columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    //     // } 
                    //     action: function (e, dt, node, config) {
                    //         // alert('Button activated');
                    //         var btn_action = 'print';
                    //         $.ajax({
                    //             url:"action.php",
                    //             method:"POST",
                    //             data:{btn_action:btn_action, first:first, second:second},
                    //             dataType:"json",
                    //             success:function(data)
                    //             {
                    //                 window.location.href = "print.php";
                    //             },error:function()
                    //             {
                    //                 toastr.info('Something went wrong.');
                    //             }
                    //         })
                    //     },
                    // },
                ],
                "responsive": true, 
                "lengthChange": true, 
                "autoWidth": false,
                "processing":true,
                "serverSide":true,
                "ordering": false,
                "order":[],
                "ajax":{
                    url:"fetch/reports_filed.php?status=Archived&first="+first+"&second="+second,
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

        var dataTable = $("#datatables").DataTable({
            "initComplete": function () { 
                $("#datatables").DataTable().buttons().container().appendTo( $('.col-sm-12:eq(0)', $("#datatables").DataTable().table().container() ) );   
            },
            "buttons": [ 
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    }
                },
                // {
                //     extend: 'pdfHtml5',
                //     // exportOptions: {
                //     //     columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                //     // } 
                //     action: function (e, dt, node, config) {
                //         // alert('Button activated');
                //         var btn_action = 'print';
                //         var first = '';
                //         var second = '';
                //         $.ajax({
                //             url:"action.php",
                //             method:"POST",
                //             data:{btn_action:btn_action, first:first, second:second},
                //             dataType:"json",
                //             success:function(data)
                //             {
                //                 window.location.href = "print.php";
                //             },error:function()
                //             {
                //                 toastr.info('Something went wrong.');
                //             }
                //         })
                //     },
                // },
            ],
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "processing":true,
            "serverSide":true,
            "ordering": false,
            "order":[],
            "ajax":{
                url:"fetch/reports_filed.php?status=Archived",
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