
    <!-- Main Footer -->
    <footer class="main-footer">
        &copy; Copyright 2023
    </footer>

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- InputMask -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- SweetAlert2 -->
<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>

<!-- Select2 -->
<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- bs-custom-file-input -->
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

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

<!-- ChartJS -->
<script src="assets/plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>

<!--jQuery Magnify-->
<script src="assets/js/jquery.magnify.js"></script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

<div id="profileModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <form method="post" id="forms_profile">
            <div class="modal-content" >
                <div class="modal-header bg-success">
                    <h4 class="modal-title"><i class='fas fa-user-circle'></i> Change Password</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <label>Email</label>
                            <input type="email" class="form-control " placeholder="Email" value="<?php echo $_SESSION["user_name"];?>" disabled />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Password</label>
                            <input type="password" name="password" id="password" class="form-control " required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-success ">
                    <input type="hidden" name="btn_action" value="change_password"/>
                    <button type="submit" class="btn btn-default pl-3 pr-3 " id="action_profile" ><i class='fas fa-save'></i> Save</button>
                    <button type="button" class="btn btn-default pl-3 pr-3 " data-dismiss="modal" ><i class='fas fa-times-circle text-danger'></i> Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function () {
        $(document).on('submit','#forms_profile', function(event){
            event.preventDefault();
            $('#action_profile').attr('disabled','disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url:"action.php",
                method:"POST",
                data:form_data,
                dataType:"json",
                success:function(data)
                {
                    $('#action_profile').attr('disabled', false);
                    if (data.status == true)
                    {
                        $('#forms_profile')[0].reset();
                        $('#profileModal').modal('hide');
                        toastr.info(data.message);
                    }
                    else 
                    {
                        toastr.info(data.message);
                    }
                },error:function()
                {
                    $('#action_profile').attr('disabled', false);
                    toastr.info('Something went wrong.');
                }
            })
        });
    });
</script>