<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Students';
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
                        <?php if ($_SESSION["user_type"] == "Superadmin") {?>
                            <div class="card-header">
                                <div class="row d-flex align-items-center">
                                    <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#addModal" class="btn btn-default pl-3 pr-3" >
                                        <i class="fa fa-plus-circle"></i> Add</button>
                                    <div class="h4">&nbsp;<?php echo $title; ?></div>
                                </div>
                                <div class="row mt-2">
                                    <div class="">
                                        <a href="assets/Student Template.xlsx" download class="text-dark btn btn-default pl-3 pr-3 mr-0 mr-sm-2"><i class="fa fa-download"></i> Download Template</a>
                                    </div>
                                    <div class="mt-2 mt-sm-0">
                                        <form mehtod="post" id="export_excel" class="" >  
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="text" class="hidden" name="btn_action" id="btn_action1" value="student_upload_excel" />
                                                        <input type="file" class="custom-file-input" name="excel_file" id="excel_file" accept=".xlsx" >
                                                        <label style="text-align: left" class="custom-file-label" for="excel_file">Upload excel(.xlsx) file </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="card-body">
                            <table id="datatables" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>STUDENT #</th>
                                        <th>LASTNAME</th>
                                        <th>FIRSTNAME</th>
                                        <th>MIDDLE</th>
                                        <th>EMAIL</th>
                                        <th>SECTION</th>
                                        <th>DATE ADDED</th>
                                        <th>STATUS</th>
                                        <th>UPDATE</th>
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
            <div class="modal-content " >
                <div class="modal-header bg-success">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <label>Student No</label>
                            <input type="text" name="student_no" id="student_no" class="form-control " required />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Lastname</label>
                            <input type="text" name="last_name" id="last_name" class="form-control " required />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Firstname</label>
                            <input type="text" name="first_name" id="first_name" class="form-control " required />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Middlename</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control " />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Email Address</label>
                            <input type="text" name="email_address" id="email_address" class="form-control "  required />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Section</label>
                            <select name="section_id" id="section_id" class="form-control " required>
                                <option value="">Select</option>
                                <?php
                                    $output = '';
                                    $rslt = fetch_all($connect,"SELECT * FROM $SECTIONS_TABLE WHERE status = 'Active' ORDER BY id DESC " );
                                    foreach($rslt as $row)
                                    {
                                        $output .= '<option value="'.$row["id"].'">'.($row["year_level"]." - ".$row["section"]).'</option>';
                                    }
                                    echo $output ;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-success">
                    <input type="hidden" name="id" id="id"/>
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
        
        $('#excel_file').change(function(){ 
            $('#export_excel').submit();  
        });  
        
        $('#export_excel').on('submit', function(event){  
            event.preventDefault(); 
            $.ajax({
                url:"action.php",
                method:"POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                dataType:"json",
                success:function(data)
                {
                    $('#export_excel')[0].reset();
                    if (data.status == true)
                    {
                        dataTable.ajax.reload();
                    }
                    else 
                    {
                        toastr.info(data.message);
                    }
                },error:function(e)
                {
                    $('#export_excel')[0].reset();
                    toastr.info('Something went wrong.');
                }
            }) 
        }); 
        
        $('#add_button').click(function(){
            $('#forms')[0].reset();
            $('.modal-title').html("<i class='fas fa-users'></i> <?php echo $title; ?>");
            $('#action').html("<i class='fas fa-plus-circle'></i> Add");
            $('#action').val("<?php echo $title; ?>_add");
            $('#btn_action').val('<?php echo $title; ?>_add');
        });
    
        $(document).on('submit','#forms', function(event){
            event.preventDefault();
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
        });
    
        $(document).on('click', '.update', function(){
            var id = $(this).attr("id");
            var btn_action = '<?php echo $title; ?>_fetch';
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{id:id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    $('#id').val(id);
                    $('#student_no').val(data.student_no);
                    $('#last_name').val(data.last_name);
                    $('#first_name').val(data.first_name);
                    $('#middle_name').val(data.middle_name);
                    $('#email_address').val(data.email_address);
                    $('#section_id').val(data.section_id);
                    $('#addModal').modal('show');
                    $('.modal-title').html("<i class='fas fa-calendar-check'></i> <?php echo $title; ?>");
                    $('#action').html("<i class='fas fa-user-edit'></i> Update");
                    $('#action').val("<?php echo $title; ?>_update");
                    $('#btn_action').val('<?php echo $title; ?>_update');
                },error:function()
                {
                    toastr.info('Something went wrong.');
                }
            })
        });
    
        $(document).on('click', '.status', function(){
            var id = $(this).attr('id');
            var status = $(this).data("status");
            var btn_action = '<?php echo $title; ?>_status';
            Swal.fire({
                icon: 'question',
                title: 'Are you sure to change the status to '+ status+'?',
                showCancelButton: true,
                showDenyButton: false,
                confirmButtonText: '<i class="fa fa-check-circle"></i> Yes',
                cancelButtonText: `<i class="fa fa-times-circle"></i> No`,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
            }).then((result) => {
                if (result.isConfirmed) 
                {
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data:{id:id, status:status, btn_action:btn_action},
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
                url:"fetch/students.php",
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
        bsCustomFileInput.init();

    });
</script>

</body>
</html>