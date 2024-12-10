<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Rooms';
include('header.php');
include('sidebar.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <?php
                // echo date('l H:i');
                // $date = '19:24'; 
                // echo date('h:i A', strtotime($date));
            ?>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-success">
                        <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                            <div class="card-header">
                                <div class="row d-flex align-items-center">
                                    <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#addModal" class="btn btn-default pl-3 pr-3" >
                                        <i class="fa fa-plus-circle"></i> Add</button>
                                    <div class="h4">&nbsp;<?php echo $title; ?></div>
                                </div>
                                <div class="row mt-2">
                                    <div class="">
                                        <a href="assets/Schedule Template.xlsx" download class="text-dark btn btn-default pl-3 pr-3 mr-0 mr-sm-2"><i class="fa fa-download"></i> Download Schedule Template</a>
                                    </div>
                                    <div class="mt-2 mt-sm-0">
                                        <form mehtod="post" id="export_excel" class="" >  
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="text" class="hidden" name="btn_action" id="btn_action1" value="scheduled_upload_excel" />
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
                                        <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                                            <!-- <th>ID</th> -->
                                            <th>LAB ID</th>
                                            <th>ROOM</th>
                                            <th>SEAT/PC</th>
                                            <th>DATE ADDED</th>
                                            <th>STATUS</th>
                                            <th>UPDATE/SCHEDULE/DETAILS</th>
                                            <!-- <th>Schedule</th>
                                            <th>Layout</th> -->
                                        <?php } else { ?>
                                            <th>LAB ID</th>
                                            <th>ROOM</th>
                                            <th>SECTION</th>
                                            <th>COURSE</th>
                                            <th>DAY</th>
                                            <th>TIME</th>
                                            <th>DETAILS</th>
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

<?php if ($_SESSION["user_type"] == 'Superadmin') {?>
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
                            <div class="form-group col-12 col-md-12 qrcode">
                                <div class="d-flex justify-content-center">
                                    <div id="qrcode"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label>Room</label>
                                <input type="text" name="room" id="room" class="form-control " required />
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label>Seats/PC</label>
                                <input type="number" min="0" name="seats" id="seats" class="form-control " required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-success ">
                        <input type="hidden" name="id" id="id"/>
                        <input type="hidden" name="btn_action" id="btn_action"/>
                        <button type="submit" class="btn btn-default pl-3 pr-3 " name="action" id="action" ><i class='fas fa-plus-circle'></i> Add</button>
                        <button type="button" class="btn btn-default pl-3 pr-3 " data-dismiss="modal" ><i class='fas fa-times-circle text-danger'></i> Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="scheduleModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" >
                <div class="modal-header bg-success">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form method="post" id="forms_scheduled">
                                <div class="row">
                                    <div class="form-group col-12 col-md-4">
                                        <label>Instructor</label>
                                        <select name="teacher_id" id="teacher_id" class="form-control " required>
                                            <option value="">Select</option>
                                            <?php
                                                $output = '';
                                                $rslt = fetch_all($connect,"SELECT * FROM $USER_TABLE WHERE user_type != 'Superadmin' AND status != 'Inactive' ORDER BY id DESC " );
                                                foreach($rslt as $row)
                                                {
                                                    $output .= '<option value="'.$row["id"].'">'.$row["fullname"].'</option>';
                                                }
                                                echo $output ;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-3">
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
                                    <div class="form-group col-12 col-md-5">
                                        <label>Course</label>
                                        <input type="text" name="subject" id="subject" class="form-control " required />
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label>Day</label>
                                        <select name="days" id="days" class="form-control " required>
                                            <option value="">Select</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label>Time In</label>
                                        <div class="input-group date" id="times_ins" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" required data-target="#times_ins" name="times_in" id="times_in" />
                                            <div class="input-group-append" data-target="#times_ins" data-toggle="datetimepicker" >
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label>Time Out</label>
                                        <div class="input-group date" id="times_outs" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" required data-target="#times_outs" name="times_out" id="times_out"  />
                                            <div class="input-group-append" data-target="#times_outs" data-toggle="datetimepicker" >
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label class="d-none d-md-block">&nbsp;</label>
                                        <input type="hidden" name="room_id" id="room_id"/>
                                        <input type="hidden" name="btn_action" id="btn_action2" value="scheduled_add"/>
                                        <button type="submit" name="action" id="action2" class="btn btn-block btn-success pl-3 pr-3" >
                                            <i class="fa fa-plus-circle"></i> Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="form-group col-12">
                            <hr class="m-0 p-0">
                        </div>
                        <div class="form-group col-12">
                            <table id="datatables_schedule" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>INSTRUCTOR</th>
                                        <th>SECTION</th>
                                        <th>COURSE</th>
                                        <th>DAY</th>
                                        <th>TIME</th>
                                        <th>DATE ADDED</th>
                                        <th>REMOVE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-success ">
                    <button type="button" class="btn btn-default pl-3 pr-3 " data-dismiss="modal" ><i class='fas fa-times-circle text-danger'></i> Close</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div id="layoutModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" >
            <div class="modal-header bg-success">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <i class="text-bold">NOTE: Click the PC # to see the details.</i>
                            </div>
                            <div class="col-12">
                                <div class="layout_laboratory row"></div>
                            </div>
                            <div class="col-12 mb-2 mt-2 text-center">
                                <hr class="m-0 p-0 mb-2">
                                <label>Components</label>
                                <hr class="m-0 p-0">
                            </div>
                            <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                                <div class="col-12">
                                    <form method="post" id="forms_components">
                                        <div class="row">
                                            <div class="form-group col-12 col-md-8">
                                                <label>Component</label>
                                                <select name="component_id" id="component_id" class="form-control " required >
                                                    <option value="">Select</option>
                                                    <?php
                                                        $output = '';
                                                        $rslt = fetch_all($connect,"SELECT * FROM $COMPONENTS_TABLE WHERE status = 'Active' ORDER BY id DESC " );
                                                        foreach($rslt as $row)
                                                        {
                                                            $output .= '<option value="'.$row["id"].'">'.($row["types"]." - ".$row["name"]).'</option>';
                                                        }
                                                        echo $output ;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-md-4">
                                                <input type="hidden" name="room_id" id="component_room_id" value="0"/>
                                                <input type="hidden" name="pc_no" id="pc_no" value="0"/>
                                                <input type="hidden" name="btn_action" id="btn_action3" value="room_components_add"/>
                                                <button type="submit" name="action" id="action3" class="btn btn-block btn-success pl-3 pr-3" disabled >
                                                    <i class="fa fa-plus-circle"></i> <?php echo $_SESSION["user_type"] == 'Superadmin' ? 'Add' : 'Request'?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php }?>
                            <div class="col-12">
                                <table id="datatables_components" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>COMPONENTS</th>
                                            <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                                                <th>REMOVE</th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-7">
                        <div class="row">
                            <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 mb-2  text-center">
                                            <hr class="m-0 p-0 mb-2">
                                            <label>Students</label>
                                            <hr class="m-0 p-0">
                                        </div>
                                        <div class="col-12">
                                            <table id="datatables_students" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>STUDENT</th>
                                                        <th>INSTRUCTOR</th>
                                                        <th>SECTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } else {?>
                                <div class="col-12">
                                    <form method="post" id="forms_students">
                                        <div class="row">
                                            <div class="col-12 mb-2  text-center">
                                                <hr class="m-0 p-0 mb-2">
                                                <label>Students</label>
                                                <hr class="m-0 p-0">
                                            </div>
                                            <div class="form-group col-12 col-md-9">
                                                <label>Student</label>
                                                <select name="student_id" id="student_id" class="form-control " required >
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-md-3">
                                                <label class="d-none d-md-block">&nbsp;</label>
                                                <input type="hidden" name="scheduled_id" id="student_scheduled_id" value="0"/>
                                                <input type="hidden" name="pc_no" id="pc_no1" value="0"/>
                                                <input type="hidden" name="btn_action" id="btn_action4" value="room_student_assign"/>
                                                <button type="submit" name="action" id="action4" class="btn btn-block btn-success pl-3 pr-3" disabled >
                                                    <i class="fa fa-user-tag"></i> Assign</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php } ?>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 mb-2 mt-2 text-center">
                                        <hr class="m-0 p-0 mb-2">
                                        <label>Reports</label>
                                        <hr class="m-0 p-0">
                                    </div>
                                    <?php if ($_SESSION["user_type"] !== 'Superadmin') {?>
                                        <div class="col-12 forms_filed hidden">
                                            <form method="post" id="forms_filed">
                                                <div class="row">
                                                    <div class="form-group col-12 col-md-12">
                                                        <i><span class="text-danger">*</span>All fields are required to file a report.</i>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="row">
                                                            <div class="form-group col-12 ">
                                                                <label class="details"></label>
                                                                <div class="text-center">
                                                                    <div class="image-upload " >
                                                                        <label for="files">
                                                                            <img class="img-thumbnail files" src="assets/image-placeholder.png" 
                                                                                style="cursor:pointer; width: 200px; height: 200px;"/>
                                                                        </label>
                                                                        <input type="file" accept=".png, .jpg, .jpeg" onchange="readURL(this);" name="files" id="files" />
                                                                    </div>
                                                                    <i>Click image to upload</i>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>Category</label>
                                                                <select class="form-control" id="category" name="category" required>
                                                                    <option value="">Select</option>
                                                                    <option value="Other">Other</option>
                                                                    <?php
                                                                        $output = '';
                                                                        $rslt = fetch_all($connect,"SELECT * FROM $CATEGORY_TABLE WHERE status = 'Active' " );
                                                                        foreach($rslt as $row)
                                                                        {
                                                                            $output .= '<option value="'.$row["category"].'">'.$row["category"].'</option>';
                                                                        }
                                                                        echo $output;
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>PC Status</label>
                                                                <select class="form-control" id="pc_status" name="pc_status" required >
                                                                    <option value="">Select</option>
                                                                    <option value="Working">Working</option>
                                                                    <option value="Not Working">Not Working</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12 col-md-12">
                                                                <label>Issue/Details</label>
                                                                <textarea name="issue" id="issue" class="form-control " required ></textarea>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <input class="hidden" id="scheduled_student_id" name="scheduled_student_id"></input>
                                                                <input type="hidden" name="pc_no" id="pc_no2" value="0"/>
                                                                <input type="hidden" name="btn_action" id="btn_action5" value="reports_file"/>
                                                                <button type="submit" name="action" id="action5" class="btn btn-block btn-success pl-3 pr-3"  >
                                                                    <i class="fa fa-save"></i> File Report</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <hr class="m-0 p-0 mb-2">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php } ?>
                                    <div class="col-12">
                                        <table id="datatables_reports" class="table table-bordered table-hover">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>SUBMITTED</th>
                                                    <th>DETAILS</th>
                                                    <th>STATUS</th>
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
            <div class="modal-footer bg-success ">
                <button type="button" class="btn btn-default pl-3 pr-3 " data-dismiss="modal" ><i class='fas fa-times-circle text-danger'></i> Close</button>
            </div>
        </div>
    </div>
</div>
  
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('.files').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(function () {
    
        <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
        
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
                            toastr.info(data.message);
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

            var qrcode = new QRCode("qrcode", {
                text: '',
                width: 128,
                height: 128,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            $('#component_status').change(function(){
                if ($(this).val() == '' || $(this).val() == 'Working')
                {
                    $('#component_remarks').removeAttr('required','required').attr('disabled','disabled');
                }
                else
                {
                    $('#component_remarks').attr('required','required').removeAttr('disabled','disabled');
                }
            });
            
            $('#add_button').click(function(){
                $('#forms')[0].reset();
                $('.modal-title').html("<i class='fas fa-user-tie'></i> <?php echo $title; ?>");
                $('#action').html("<i class='fas fa-plus-circle'></i> Add");
                $('#action').val("<?php echo $title; ?>_add");
                $('#btn_action').val('<?php echo $title; ?>_add');
                $('.qrcode').addClass('hidden');
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
                        $('.qrcode').removeClass('hidden');
                        qrcode.makeCode(data.lab_id);
                        $('#room').val(data.room);
                        $('#seats').val(data.seats);
                        $('#addModal').modal('show');
                        $('.modal-title').html("<i class='fas fa-user-tie'></i> <?php echo $title; ?>");
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
    
            $(document).on('click', '.schedule', function(){
                var id = $(this).attr("id");
                var room = $(this).data("room");
                $('#forms_scheduled')[0].reset();
                $('#scheduleModal').modal('show');
                $('#scheduleModal .modal-title').html("<i class='fas fa-calendar-alt'></i> Schedule - " + room);
                $('#times_in').val('');
                $('#times_out').val('');
                $('#room_id').val(id);
                $('#action2').val('scheduled_add');
                $('#btn_action2').val('scheduled_add');
                
                $('#datatables_schedule').DataTable().destroy();
                dataTableScheduled = $('#datatables_schedule').DataTable({
                    "responsive": true, 
                    "lengthChange": true, 
                    "autoWidth": false,
                    "processing":true,
                    "serverSide":true,
                    "ordering": false,
                    "order":[],
                    "ajax":{
                        url:"fetch/scheduled.php?room_id="+id,
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

            $(document).on('submit','#forms_scheduled', function(event){
                event.preventDefault();
                $('#action2').attr('disabled','disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"json",
                    success:function(data)
                    {
                        $('#action2').attr('disabled', false);
                        if (data.status == true)
                        {
                            $('#forms_scheduled')[0].reset();
                            dataTableScheduled.ajax.reload();
                        }
                        else 
                        {
                            toastr.info(data.message);
                        }
                    },error:function()
                    {
                        $('#action2').attr('disabled', false);
                        toastr.info('Something went wrong.');
                    }
                })
            });

            $(document).on('click', '.remove_scheduled', function(){
                var id = $(this).attr("id");
                var btn_action = 'scheduled_remove';
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:{id:id, btn_action:btn_action},
                    dataType:"json",
                    success:function(data)
                    {
                        dataTableScheduled.ajax.reload();
                    },error:function()
                    {
                        toastr.info('Something went wrong.');
                    }
                })
            });

            var dataTableScheduled = $("#datatables_schedule").DataTable({
                "responsive": true, 
                "lengthChange": true, 
                "autoWidth": false,
                "ordering": false,
                "searching": false,
                "info": true,
                "order":[],
                "columnDefs":[
                    {
                    "targets":[0],
                    "orderable":false,
                    },
                ],
                "pageLength": 10, 
            });

            $('#times_ins').datetimepicker({
                icons: {
                    time: "fa fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                },
                format: 'hh:mm A',
                enabledHours: [7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
            });

            $('#times_outs').datetimepicker({
                icons: {
                    time: "fa fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                },
                format: 'hh:mm A',
                enabledHours: [7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
            });
        <?php } if ($_SESSION["user_type"] == 'Staff') {?>
            $(document).on('submit','#forms_filed', function(event){
                event.preventDefault();
                $('#action5').attr('disabled','disabled');
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
                        $('#action5').attr('disabled', false);
                        if (data.status == true)
                        {
                            $('#category').val('');
                            $('#pc_status').val('');
                            $('#issue').val('');
                            $('.files').attr('src', 'assets/image-placeholder.png');
                            $('#layoutModal').modal('hide');
                            // $('#forms_filed')[0].reset();
                            toastr.info(data.message);
                        }
                        else 
                        {
                            toastr.info(data.message);
                        }
                    },error:function()
                    {
                        $('#action5').attr('disabled', false);
                        toastr.info('Something went wrong.');
                    }
                })
            });
        <?php } ?>

        var dataTable = $("#datatables").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "processing":true,
            "serverSide":true,
            "ordering": false,
            "order":[],
            "ajax":{
                url:"fetch/rooms.php",
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
    
        var room_id = 0;
        $(document).on('click', '.layout', function(){
            room_id = $(this).attr("id");
            $('#component_room_id').val(room_id);
            var room = $(this).data("room");
            $('#layoutModal').modal('show');
            $('#layoutModal .modal-title').html("<i class='fa fa-info-circle'></i> Details - " + room);
            
            <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                $('#forms_components')[0].reset();
                // $('#layoutModal .modal-title').html("<i class='fas fa-map'></i> Layout - " + room);
            <?php } ?>

            $('#action3').attr('disabled','disabled');
            $('#component_remarks').removeAttr('required','required').attr('disabled','disabled');
            var btn_action = 'layout_load';
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{id:room_id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    $('.layout_laboratory').html(data.layout);
                },error:function()
                {
                    toastr.info('Something went wrong.');
                }
            })

            $('#datatables_components').DataTable().destroy();
            dataTableComponents = $("#datatables_components").DataTable({
                "responsive": true, 
                "lengthChange": true, 
                "autoWidth": false,
                "processing":true,
                "serverSide":true,
                "ordering": false,
                "searching": false,
                "paging": false,
                "info": false,
                "order":[],
                "ajax":{
                    url:"fetch/rooms_components.php?room_id=0&pc_no=0",
                    type:"POST"
                },
                "columnDefs":[
                    {
                    "targets":[0],
                    "orderable":false,
                    },
                ],
                "pageLength": -1, 
            });

            <?php if ($_SESSION["user_type"] !== 'Superadmin') {?>
                var scheduled_id = $(this).data("scheduled_id");
                $('#student_scheduled_id').val(scheduled_id);
                $('#scheduled_student_id').val(scheduled_id);
                $('#forms_students')[0].reset();
                $('#action4').attr('disabled','disabled');
                loadStudents(scheduled_id, 0);
                $('.forms_filed').addClass('hidden');
            <?php } else {?>
                $('#datatables_students').DataTable().destroy();
                dataTableStudents = $("#datatables_students").DataTable({
                    "responsive": true, 
                    "lengthChange": true, 
                    "autoWidth": false,
                    "processing":true,
                    "serverSide":true,
                    "ordering": false,
                    "searching": false,
                    "paging": false,
                    "info": false,
                    "order":[],
                    "ajax":{
                        url:"fetch/rooms_students.php?room_id=0&pc_no=0",
                        type:"POST"
                    },
                    "columnDefs":[
                        {
                        "targets":[0],
                        "orderable":false,
                        },
                    ],
                    "pageLength": -1, 
                });
            <?php } ?>

            $('#datatables_reports').DataTable().destroy();
            dataTableReports = $("#datatables_reports").DataTable({
                "responsive": true, 
                "lengthChange": true, 
                "autoWidth": false,
                "ordering": false,
                "searching": false,
                "info": true,
                "order":[],
                "columnDefs":[
                    {
                    "targets":[0],
                    "orderable":false,
                    },
                ],
                "pageLength": 10, 
            });
            dataTableReports.clear().draw();
        });

        <?php if ($_SESSION["user_type"] !== 'Superadmin') {?>
            function loadStudents(scheduled_id, pc_no)
            {
                var btn_action = 'students_load';
                var scheduled_id = scheduled_id;
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:{scheduled_id:scheduled_id, pc_no:pc_no, btn_action:btn_action},
                    dataType:"json",
                    success:function(data)
                    {
                        $('#student_id').html(data.students);
                    },error:function()
                    {
                        toastr.info('Something went wrong.');
                    }
                })
            }
            
            $(document).on('submit','#forms_students', function(event){
                event.preventDefault();
                $('#action4').attr('disabled','disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"json",
                    success:function(data)
                    {
                        $('#action4').attr('disabled', false);
                        if (data.status == true)
                        {
                            toastr.info(data.message);
                        }
                        else 
                        {
                            toastr.info(data.message);
                        }
                    },error:function()
                    {
                        $('#action4').attr('disabled', false);
                        toastr.info('Something went wrong.');
                    }
                })
            });
        <?php } else {?>
            var dataTableStudents = $("#datatables_students").DataTable({
                "responsive": true, 
                "lengthChange": true, 
                "autoWidth": false,
                "ordering": false,
                "searching": false,
                "paging": false,
                "info": false,
                "order":[],
                "columnDefs":[
                    {
                    "targets":[0],
                    "orderable":false,
                    },
                ],
                "pageLength": -1, 
            });
        <?php } ?>
    
        $(document).on('submit','#forms_components', function(event){
            event.preventDefault();
            $('#action3').attr('disabled','disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url:"action.php",
                method:"POST",
                data:form_data,
                dataType:"json",
                success:function(data)
                {
                    $('#action3').attr('disabled', false);
                    if (data.status == true)
                    {
                        $('#forms_components')[0].reset();
                        dataTableComponents.ajax.reload();
                    }
                    else 
                    {
                        toastr.info(data.message);
                    }
                },error:function()
                {
                    $('#action3').attr('disabled', false);
                    toastr.info('Something went wrong.');
                }
            })
        });
    
        $(document).on('click', '.remove_components', function(){
            var id = $(this).attr("id");
            var btn_action = 'room_components_remove';
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{id:id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    dataTableComponents.ajax.reload();
                },error:function()
                {
                    toastr.info('Something went wrong.');
                }
            })
        });
    
        $(document).on('click', '.accept_components', function(){
            var id = $(this).attr("id");
            var btn_action = 'room_components_accept';
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{id:id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    dataTableComponents.ajax.reload();
                },error:function()
                {
                    toastr.info('Something went wrong.');
                }
            })
        });

        $(document).on('click', '.pc_no_layout', function(){
            var pc_no_id = $(this).attr("id");
            var pc_no = $(this).data("pc_no");
            $('.forms_filed').addClass('hidden');
            if ($('#'+pc_no_id).hasClass('bg-secondary'))
            {
                $('#'+pc_no_id).removeClass('bg-secondary');
                $('#pc_no').val('0');
                $('#action3').attr('disabled','disabled');
                <?php if ($_SESSION["user_type"] !== 'Superadmin') {?>
                    $('#pc_no1').val('0');
                    $('#action4').attr('disabled','disabled');
                    var scheduled_id = $('#student_scheduled_id').val();
                    loadStudents(scheduled_id, 0);

                <?php } else { ?> 
                    $('#datatables_students').DataTable().destroy();
                    dataTableStudents = $("#datatables_students").DataTable({
                        "responsive": true, 
                        "lengthChange": true, 
                        "autoWidth": false,
                        "ordering": false,
                        "searching": false,
                        "paging": false,
                        "info": false,
                        "order":[],
                        "columnDefs":[
                            {
                            "targets":[0],
                            "orderable":false,
                            },
                        ],
                        "pageLength": -1, 
                    });
                    dataTableStudents.clear().draw();
                <?php } ?> 
                
                $('#datatables_components').DataTable().destroy();
                dataTableComponents = $("#datatables_components").DataTable({
                    "responsive": true, 
                    "lengthChange": true, 
                    "autoWidth": false,
                    "ordering": false,
                    "searching": false,
                    "paging": false,
                    "info": false,
                });
                dataTableComponents.clear().draw();

                $('#datatables_reports').DataTable().destroy();
                dataTableReports = $("#datatables_reports").DataTable({
                    "responsive": true, 
                    "lengthChange": true, 
                    "autoWidth": false,
                    "ordering": false,
                    "searching": false,
                    "info": true,
                    "order":[],
                    "columnDefs":[
                        {
                        "targets":[0],
                        "orderable":false,
                        },
                    ],
                    "pageLength": 10, 
                });
                dataTableReports.clear().draw();
            }
            else
            {
                $('.pc_no_layout').removeClass('bg-secondary');
                $('#'+pc_no_id).addClass('bg-secondary');
                $('#pc_no').val(pc_no);
                $('#action3').removeAttr('disabled','disabled');
                <?php if ($_SESSION["user_type"] !== 'Superadmin') {?>
                    $('#pc_no1').val(pc_no);
                    $('#pc_no2').val(pc_no);
                    $('#action4').removeAttr('disabled','disabled');
                    $('.forms_filed').removeClass('hidden');
                <?php } ?>

                $('#datatables_components').DataTable().destroy();
                dataTableComponents = $('#datatables_components').DataTable({
                    "responsive": true, 
                    "lengthChange": true, 
                    "autoWidth": false,
                    "processing":true,
                    "serverSide":true,
                    "ordering": false,
                    "searching": false,
                    "paging": false,
                    "info": false,
                    "order":[],
                    "ajax":{
                        url:"fetch/rooms_components.php?room_id="+room_id+"&pc_no="+pc_no,
                        type:"POST"
                    },
                    "columnDefs":[
                        {
                        "targets":[0],
                        "orderable":false,
                        },
                    ],
                    "pageLength": -1, 
                });

                <?php if ($_SESSION["user_type"] !== 'Superadmin') {?>
                    var scheduled_id = $('#student_scheduled_id').val();
                    loadStudents(scheduled_id, pc_no);
                    $('#datatables_reports').DataTable().destroy();
                    dataTableReports = $('#datatables_reports').DataTable({
                        "responsive": true, 
                        "lengthChange": true, 
                        "autoWidth": false,
                        "processing":true,
                        "serverSide":true,
                        "ordering": false,
                        "order":[],
                        "ajax":{
                            url:"fetch/reports_filed.php?room_id="+room_id+"&pc_no="+pc_no+"&scheduled_id="+scheduled_id,
                            type:"POST"
                        },
                        "columnDefs":[
                            {
                            "targets":[0],
                            "orderable":false,
                            },
                        ],
                        "pageLength": -1, 
                    });
                <?php } else {?>
                    $('#datatables_students').DataTable().destroy();
                    dataTableStudents = $('#datatables_students').DataTable({
                        "responsive": true, 
                        "lengthChange": true, 
                        "autoWidth": false,
                        "processing":true,
                        "serverSide":true,
                        "ordering": false,
                        "searching": false,
                        "paging": false,
                        "info": false,
                        "order":[],
                        "ajax":{
                            url:"fetch/rooms_students.php?room_id="+room_id+"&pc_no="+pc_no,
                            type:"POST"
                        },
                        "columnDefs":[
                            {
                            "targets":[0],
                            "orderable":false,
                            },
                        ],
                        "pageLength": -1, 
                    });
                    
                    $('#datatables_reports').DataTable().destroy();
                    dataTableReports = $('#datatables_reports').DataTable({
                        "responsive": true, 
                        "lengthChange": true, 
                        "autoWidth": false,
                        "processing":true,
                        "serverSide":true,
                        "ordering": false,
                        "order":[],
                        "ajax":{
                            url:"fetch/reports_filed.php?room_id="+room_id+"&pc_no="+pc_no,
                            type:"POST"
                        },
                        "columnDefs":[
                            {
                            "targets":[0],
                            "orderable":false,
                            },
                        ],
                        "pageLength": -1, 
                    });
                <?php } ?>
            }
        });
            
        var dataTableComponents = $("#datatables_components").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "ordering": false,
            "searching": false,
            "paging": false,
            "info": false,
        });

        var dataTableReports = $("#datatables_reports").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "ordering": false,
            "searching": false,
            "info": true,
            "order":[],
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