<?php
include('config.php');

if (isset($_SESSION['user_type']))
{
  header("location:dashboard.php");
}

$title = 'Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cavite State University</title>
    <!-- <link rel="icon" href="assets/logo.jpg" type="image/ico"> -->
    <link rel="icon" href="assets/logocvsu.png" type="image/ico">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">

    <!--jQuery Magnify-->
    <link rel="stylesheet" href="assets/css/jquery.magnify.css">
    <style>
        .magnify-button-close, 
        .magnify-button-maximize, 
        .magnify-button-rotateRight, 
        .magnify-button-actualSize, 
        .magnify-button-next, 
        .magnify-button-fullscreen, 
        .magnify-button-prev, 
        .magnify-button-zoomIn, 
        .magnify-button-zoomOut
        {
            color: white;
        }
        #html5-qrcode-anchor-scan-type-change
        {
            display: none!important;
        }
        [type=button]
        {
            /* display: none!important; */
            background-color: #f8f9fa;
            border-color: #ddd;
            color: #444;
            padding-left: 1rem!important;
            padding-right: 1rem!important;
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        body 
        {
            background: green url("assets/bg2.png") no-repeat;
            background-size:100% 100%;
        }
        .hidden 
        {
            display: none;
        }
        .image-upload > input 
        {
            visibility:hidden;
            width: 0;
            height: 0;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <img src="assets/logo.jpg" alt="Logo" class="img-circle elevation-3" style="width: 40%; height: 40%;">
        <h1 class="text-white text-bold" style="text-shadow: 1px 1px 2px black;">Cavite State University</h1>
    </div>
    <div class="card">
        <div class="card-body login-card-body bg-success">
            <p class="login-box-msg">Sign in to start your session</p>

            <form method="post" id="forms">
                <div class="form-group" >
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control " required />
                </div>
                <div class="form-group ">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control " required />
                </div>
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="btn_action" id="btn_action" value="sign_in"/>
                        <!-- <button type="submit" class="btn btn-default btn-block" name="action" id="action"><i class='fas fa-sign-in-alt'></i> Sign In</button> -->
                        <button type="submit" class="btn btn-default btn-block " name="action" id="action" ><i class='fas fa-sign-in-alt'></i> Sign In</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-0">
                <a href="#" class="text-white" name="btn_activate" id="btn_activate" data-toggle="modal" data-target="#addModal">Activate account</a>
            </p>
            <!-- <p class="">
                <a href="#" class="text-white" name="btn_reset" id="btn_reset" data-toggle="modal" data-target="#addModal">Reset Password?</a>
            </p> -->
            <p class="mb-4">
                <a href="#" class="text-white" name="btn_reset" id="btn_reset" data-toggle="modal" data-target="#addModal">Reset Password?</a>
            </p>
            <!-- <button type="button" class="btn btn-default btn-block btn_scan" data-toggle="modal" data-target="#scanModal" ><i class='fas fa-qrcode'></i> Scan QR Code</button> -->
            
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Select2 -->
<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- SweetAlert2 -->
<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>

<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>

<script src="assets/js/html5-qrcode.min.js"></script>

<!--jQuery Magnify-->
<script src="assets/js/jquery.magnify.js"></script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<div id="addModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <form method="post" id="forms_activate">
            <div class="modal-content " >
                <div class="modal-header bg-success">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <label>Email</label>
                            <input type="email" name="email" id="email_activate" class="form-control " required />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Email Code</label>
                            <input type="number" min="1" name="email_code" id="email_code" class="form-control " disabled />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>New Password</label>
                            <input type="password" min="0" name="password" id="password_activate" class="form-control " disabled />
                        </div>
                        <!-- <div class="form-group col-12 col-md-6">
                            <input type="password" min="0" name="confirm" id="confirm_activate" class="form-control " placeholder="Confirm Password" disabled />
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer bg-success">
                    <input type="hidden" name="user_type" id="user_type"/>
                    <input type="hidden" name="id" id="id"/>
                    <input type="hidden" name="title" id="title"/>
                    <input type="hidden" name="steps" id="steps"/>
                    <input type="hidden" name="btn_action" id="btn_action_activate"/>
                    <button type="submit" class="btn btn-default pl-3 pr-3 " name="action" id="action_activate" ><i class='fas fa-plus-circle'></i> Add</button>
                    <button type="button" class="btn btn-default pl-3 pr-3 " data-dismiss="modal" ><i class='fas fa-times-circle text-danger'></i> Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="scanModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content " >
            <div class="modal-header bg-success">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="qr-reader" ></div>
                        <div id="qr-reader-results"></div>
                    </div>
                    <div class="col-12">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-success">
                <button type="button" class="btn btn-default pl-3 pr-3 " data-dismiss="modal" ><i class='fas fa-times-circle text-danger'></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div id="reportsModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <form method="post" id="forms_reports">
            <div class="modal-content " >
                <div class="modal-header bg-success">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label>PC #</label>
                            <input type="text" class="form-control pc_no" disabled />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>PC Status</label>
                            <input type="text" class="form-control pc_status" disabled />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Room</label>
                            <input type="text" class="form-control room_name" disabled />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Section</label>
                            <input type="text" class="form-control section_name" disabled />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Instructor</label>
                            <input type="text" class="form-control teacher_name" disabled />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Course</label>
                            <input type="text" class="form-control subject" disabled />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Day</label>
                            <input type="text" class="form-control day" disabled />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <hr class="m-0 p-0 mb-2">
                            <i><span class="text-danger">*</span>All fields are required to file a report.</i>
                        </div>
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
                        <div class="form-group col-12 col-md-7">
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
                        <div class="form-group col-12 col-md-5">
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
                    </div>
                </div>
                <div class="modal-footer bg-success">
                    <input class="hidden" id="scheduled_student_id" name="scheduled_student_id"></input>
                    <!-- <input type="hidden" name="student_id" id="student_id"/> -->
                    <input type="hidden" name="btn_action" id="btn_action_reports"/>
                    <button type="submit" class="btn btn-default pl-3 pr-3 " name="action" id="action_reports" >
                        <i class='fas fa-save'></i> Submit
                    </button>
                    <button type="button" class="btn btn-default pl-3 pr-3 " data-dismiss="modal" ><i class='fas fa-times-circle text-danger'></i> Close</button>
                </div>
            </div>
        </form>
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
        $('.select2').select2();

        var lab_no;
        var teacher_id = '';
        var section = '';

        $("#reportModal").on("hidden.bs.modal", function () {
            $("#viewModal").modal('show');
            $('.btn_report').attr('disabled', 'disabled');
            // put your default event here
        });
        
        $('#btn_activate').click(function(){
            $('#forms_activate')[0].reset();
            $('.modal-title').html("<i class='fas fa-user-cog'></i> Activate Account");
            $('#action_activate').html("<i class='fas fa-save'></i> Activate");
            $('#action_activate').val("send_email");
            $('#btn_action_activate').val("send_email");
            $('#steps').val('1');
            $('#title').val("activate");
            $('#email_activate').removeAttr('disabled','disabled').attr('required','required');
            $('#email_code').attr('disabled','disabled').removeAttr('required','required');
            $('#password_activate').attr('disabled','disabled').removeAttr('required','required');
            // $('#confirm_activate').attr('disabled','disabled').removeAttr('required','required');
            toastr.success('Please enter your email.');
        });
        
        $('#btn_reset').click(function(){
            $('#forms_activate')[0].reset();
            $('.modal-title').html("<i class='fas fa-question-circle'></i> Reset Password");
            $('#action_activate').html("<i class='fas fa-save'></i> Reset");
            $('#steps').val('1');
            $('#title').val("forgot");
            $('#action_activate').val("send_email");
            $('#btn_action_activate').val("send_email");
            $('#email_activate').removeAttr('disabled','disabled').attr('required','required');
            $('#email_code').attr('disabled','disabled').removeAttr('required','required');
            $('#password_activate').attr('disabled','disabled').removeAttr('required','required');
            // $('#confirm_activate').attr('disabled','disabled').removeAttr('required','required');
            toastr.success('Please enter your email.');
        });
    
        $(document).on('submit','#forms_activate', function(event){
            event.preventDefault();
            $('#action_activate').attr('disabled','disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url:"action.php",
                method:"POST",
                data:form_data,
                dataType:"json",
                success:function(data)
                {
                    $('#action_activate').attr('disabled', false);
                    if (data.status == true)
                    {
                        if ($('#steps').val() == '1')
                        {
                            $('#email_activate').attr('disabled','disabled').removeAttr('required','required');
                            $('#email_code').removeAttr('disabled','disabled').attr('required','required');
                            $('#steps').val('2');
                            $('#id').val(data.id);
                            $('#user_type').val(data.user_type);
                            toastr.success('Please enter an email code from your email.');
                        }
                        else if ($('#steps').val() == '2')
                        {
                            $('#email_code').attr('disabled','disabled').removeAttr('required','required');
                            $('#password_activate').removeAttr('disabled','disabled').attr('required','required');
                            // $('#confirm_activate').removeAttr('disabled','disabled').attr('required','required');
                            $('#steps').val('3');
                            toastr.success('Please enter your new password.');
                        }
                        else
                        {
                            $('#addModal').modal('hide');
                            toastr.success('Use your email and new password to login.');
                        }
                    }
                    else 
                    {
                        toastr.warning(data.message);
                    }
                },error:function()
                {
                    $('#action_activate').attr('disabled', false);
                    toastr.warning('Something went wrong.');
                }
            })
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
                        if (data.user_type !== 'Student')
                        {
                            window.location.href = "dashboard.php";
                        }
                        else
                        {
                            $('#forms')[0].reset();
                            var student_id = data.user_id;
                            var student_name = data.fullname;
                            
                            $("#scanModal").modal('show');
                            $('.modal-title').html("<i class='fas fa-qrcode'></i> Scan QR Code");
                            function docReady(fn) {
                                // see if DOM is already available
                                if (document.readyState === "complete"
                                    || document.readyState === "interactive") {
                                    // call on next available tick
                                    setTimeout(fn, 1);
                                } else {
                                    document.addEventListener("DOMContentLoaded", fn);
                                }
                            }
                            docReady(function () {
                                // var resultContainer = document.getElementById('qr-reader-results');
                                var lastResult, countResults = 0;
                                function onScanSuccess(decodedText, decodedResult) {
                                    if (decodedText !== lastResult) {
                                        ++countResults;
                                        lastResult = decodedText;
                                        // Handle on success condition with the decoded message.
                                        // console.log(`Scan result ${decodedText}`, decodedResult);

                                        // alert(decodedText);
                                        // lab_no = decodedText;
                                        $('#scanModal').modal('hide');
                                        // cosnole.log(decodedText);
                                        
                                        var lab_id = decodedText;
                                        // var lab_id = 'LC-2023060001';
                                        var btn_action = 'scanner';
                                        $.ajax({
                                            url:"action.php",
                                            method:"POST",
                                            data:{lab_id:lab_id, student_id:student_id, btn_action:btn_action},
                                            dataType:"json",
                                            success:function(data)
                                            {
                                                if (data.status)
                                                {
                                                    // show scan modal then show reports modal
                                                    $('#reportsModal').modal('show');
                                                    $('.modal-title').html("<i class='fas fa-info-circle'></i> Welcome " + student_name);
                                                    // $('#action_reports').val("file_reports");
                                                    // $('#btn_action_reports').val("file_reports");
                                                    // $('#student_id').val(data.user_id);
                                                    $('#forms_reports')[0].reset();
                                                    $('.subject').val(data.subject);
                                                    $('.day').val(data.day);
                                                    $('.pc_no').val(data.pc_no);
                                                    $('.pc_status').val(data.pc_status);
                                                    $('.room_name').val(data.room_name);
                                                    $('.section_name').val(data.section_name);
                                                    $('.teacher_name').val(data.teacher_name);
                                                    $('#scheduled_student_id').val(data.scheduled_student_id);
                                                    $('#action_reports').val("reports_file");
                                                    $('#btn_action_reports').val("reports_file");
                                                    $('.files').attr('src', "assets/image-placeholder.png");
                                                    // $('#forms')[0].reset();

                                                }
                                                else 
                                                {
                                                    toastr.info(data.message);
                                                }
                                            },error:function()
                                            {
                                                toastr.warning('Something went wrong.');
                                            }
                                        })

                                    }
                                }

                                var html5QrcodeScanner = new Html5QrcodeScanner(
                                    "qr-reader", { fps: 10, qrbox: 250 });
                                html5QrcodeScanner.render(onScanSuccess);
                            });

                        }
                    }
                    else 
                    {
                        toastr.warning(data.message);
                    }
                },error:function()
                {
                    $('#action').attr('disabled', false);
                    toastr.warning('Something went wrong.');
                }
            })
        });
    
        var scheduled_id = 0;
        $(document).on('submit','#forms_reports', function(event){
            event.preventDefault();
            $('#action_reports').attr('disabled','disabled');
            // var category = $('#category').val();
            // var pc_status = $('#pc_status').val();
            // var issue = $('#issue').val();
            // var form_data = $(this).serialize();
            // var btn_action = 'reports_file';
            $.ajax({
                url:"action.php",
                method:"POST",
                // data:{category:category, pc_status:pc_status, issue:issue, scheduled_student_id:scheduled_id, btn_action:btn_action},
                // data:form_data,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                dataType:"json",
                success:function(data)
                {
                    $('#action_reports').attr('disabled', false);
                    if (data.status)
                    {
                        $('#reportsModal').modal('hide');
                        $('#forms_reports')[0].reset();
                        // $('.file_report').addClass('hidden');
                        // $('#category').attr('required', false);
                        // $('#status').attr('required', false);
                        // $('#issue').attr('required', false);
                        // scheduled_id = 0;
                        toastr.success(data.message);
                        // $('tr td').removeClass('bg-success');
                    }
                    else 
                    {
                        toastr.info(data.message);
                    }
                },error:function()
                {
                    $('#action_reports').attr('disabled', false);
                    toastr.warning('Something went wrong.');
                }
            })
        });

    });
</script>

</body>
</html>