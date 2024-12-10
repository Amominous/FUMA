<?php
    
include('config.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'sign_in' ) //
    {
        $result = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE email = '".trim($_POST["email"])."' ");
        if($result)
        {
            if($result['status'] == 'Inactive')
            {
                $output['status'] = false;
                $output['message'] = 'Your account is inactive, please contact the school administrator.';
                echo json_encode($output);
                return;
            }

            if($result['status'] == 'Active')
            {
                if(password_verify(trim($_POST["password"]), $result["password"]))
                {
                    $_SESSION['fullname']  = $result['fullname'];
                    $_SESSION['user_name']  = $result['email'];
                    $_SESSION['user_type']  = $result['user_type'];
                    $_SESSION['user_id']    = $result['id'];
                    $output['status'] = true;
                    $output['user_type'] = $result['user_type'];
                    echo json_encode($output);
                    return;
                }
                
                $output['status'] = false;
                $output['message'] = 'Password does not match!';
                echo json_encode($output);
                return;
            }
            
            $output['status'] = false;
            $output['message'] = 'Please activate your account account!';
            echo json_encode($output);
            return;
        }
        
        $result = fetch_row($connect, "SELECT * FROM $STUDENT_TABLE WHERE email_address = '".trim($_POST["email"])."' ");
        if($result)
        {
            if($result['status'] == 'Inactive')
            {
                $output['status'] = false;
                $output['message'] = 'Your account is inactive, please contact the school administrator.';
                echo json_encode($output);
                return;
            }

            if($result['status'] == 'Active')
            {
                if(password_verify(trim($_POST["password"]), $result["password"]))
                {
                    // $_SESSION['user_id']    = $result['id'];
                    $output['status'] = true;
                    $output['user_type'] = 'Student';
                    $output['user_id'] = $result['id'];
                    $output['fullname']  = $result['first_name']." ".$result['middle_name']." ".$result['last_name'];
                    echo json_encode($output);
                    return;
                }
                
                $output['status'] = false;
                $output['message'] = 'Password does not match!';
                echo json_encode($output);
                return;
            }
            
            $output['status'] = false;
            $output['message'] = 'Please activate your account account!';
            echo json_encode($output);
            return;
        }
        
        $output['status'] = false;
        $output['message'] = 'Email does not exist';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'change_password' ) //
    {
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $USER_TABLE SET 
            password = '".$password."'
        WHERE id = '".$_SESSION['user_id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully saved.';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'send_email' ) //
    {
        $output['status'] = true;
        if ($_POST['steps'] == '1')
        {
            $result = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE email = '".trim($_POST["email"])."' ");
            if ($result)
            {
                if ($_POST['title'] == 'activate')
                {
                    if ($result["status"] !== 'Not Activated')
                    {
                        $output['status'] = false;
                        $output['message'] = 'Your account has already been active.';
                        echo json_encode($output);
                        return;
                    }
                }
                else
                {
                    if ($result["status"] == 'Not Activated')
                    {
                        $output['status'] = false;
                        $output['message'] = 'Please activate your account first.';
                        echo json_encode($output);
                        return;
                    }
                    else if ($result["status"] == 'Inactive')
                    {
                        $output['status'] = false;
                        $output['message'] = 'Your account is inactive, please contact the school administrator.';
                        echo json_encode($output);
                        return;
                    }
                }
                
                $email_code = rand(999, 9999);
                $connect->beginTransaction();
                $update = query($connect, "UPDATE $USER_TABLE SET email_code = '".$email_code."' WHERE email = '".trim($_POST["email"])."' ");
                if ($update == true)
                {
                    // $connect->commit();
                    // $output['id'] = $result['id'];
                    // $output['status'] = true;
                    // $output['message'] = 'Successfully updated.';
                    // $output['user_type'] = 'School';
                    // echo json_encode($output);
                    // return;

                    // send email
                    if ($_POST['title'] == 'activate')
                    {
                        $mail = send_mail(trim($_POST["email"]), 
                        trim($result['fullname']), 
                        'Account Activation', 
                        'Greetings, 
                        <br> <br>Activate your by using this email code: '.$email_code.' <br> <br> 
                        Cavite State University <br> <br> <i>This is a system generated email. Do not reply.<i>');
                        if ($mail)
                        {
                            $connect->commit();
                            $output['id'] = $result['id'];
                            $output['status'] = true;
                            $output['message'] = 'Successfully updated.';
                            $output['user_type'] = 'School';
                            echo json_encode($output);
                            return;
                        }
                    }
                    else
                    {
                        $mail = send_mail(trim($_POST["email"]), 
                        trim($result['fullname']), 
                        'Reset Password', 
                        'Greetings, 
                        <br> <br>Reset password by using this email code: '.$email_code.' <br> <br> 
                        Cavite State University <br> <br> <i>This is a system generated email. Do not reply.<i>');
                        if ($mail)
                        {
                            $connect->commit();
                            $output['id'] = $result['id'];
                            $output['status'] = true;
                            $output['message'] = 'Successfully updated.';
                            $output['user_type'] = 'School';
                            echo json_encode($output);
                            return;
                        }
                    }
                }
                
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Something went wrong.';
                echo json_encode($output);
                return;
            }
            
            $result = fetch_row($connect, "SELECT * FROM $STUDENT_TABLE WHERE email_address = '".trim($_POST["email"])."' ");
            if ($result)
            {
                if ($_POST['title'] == 'activate')
                {
                    if ($result["status"] !== 'Not Activated')
                    {
                        $output['status'] = false;
                        $output['message'] = 'Your account has already been active.';
                        echo json_encode($output);
                        return;
                    }
                }
                else
                {
                    if ($result["status"] == 'Not Activated')
                    {
                        $output['status'] = false;
                        $output['message'] = 'Please activate your account first.';
                        echo json_encode($output);
                        return;
                    }
                    else if ($result["status"] == 'Inactive')
                    {
                        $output['status'] = false;
                        $output['message'] = 'Your account is inactive, please contact the school administrator.';
                        echo json_encode($output);
                        return;
                    }
                }
                
                $email_code = rand(999, 9999);
                $connect->beginTransaction();
                $update = query($connect, "UPDATE $STUDENT_TABLE SET email_code = '".$email_code."' WHERE email_address = '".trim($_POST["email"])."' ");
                if ($update == true)
                {
                    // $connect->commit();
                    // $output['id'] = $result['id'];
                    // $output['status'] = true;
                    // $output['message'] = 'Successfully updated.';
                    // $output['user_type'] = 'Student';
                    // echo json_encode($output);
                    // return;

                    // send email
                    if ($_POST['title'] == 'activate')
                    {
                        $mail = send_mail(trim($_POST["email"]), 
                        $result['first_name']." ".$result['last_name'], 
                        'Account Activation', 
                        'Greetings, 
                        <br> <br>Activate your by using this email code: '.$email_code.' <br> <br> 
                        Cavite State University <br> <br> <i>This is a system generated email. Do not reply.<i>');
                        if ($mail)
                        {
                            $connect->commit();
                            $output['id'] = $result['id'];
                            $output['status'] = true;
                            $output['message'] = 'Successfully updated.';
                            $output['user_type'] = 'Student';
                            echo json_encode($output);
                            return;
                        }
                    }
                    else
                    {
                        $mail = send_mail(trim($_POST["email"]), 
                        $result['first_name']." ".$result['last_name'], 
                        'Reset Password', 
                        'Greetings, 
                        <br> <br>Reset password by using this email code: '.$email_code.' <br> <br> 
                        Cavite State University <br> <br> <i>This is a system generated email. Do not reply.<i>');
                        if ($mail)
                        {
                            $connect->commit();
                            $output['id'] = $result['id'];
                            $output['status'] = true;
                            $output['message'] = 'Successfully updated.';
                            $output['user_type'] = 'Student';
                            echo json_encode($output);
                            return;
                        }
                    }
                }
                
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Something went wrong.';
                echo json_encode($output);
                return;
            }
            
            $output['status'] = false;
            $output['message'] = 'Email does not exist';
            echo json_encode($output);
            return;
        }
        else if ($_POST['steps'] == '2')
        {
            $table = $_POST['user_type'] == 'Student' ? $STUDENT_TABLE : $USER_TABLE;
            $result = fetch_row($connect, "SELECT * FROM $table WHERE id = '".trim($_POST["id"])."' AND email_code = '".trim($_POST["email_code"])."' ");
            if ($result)
            {
                $output['status'] = true;
                $output['message'] = 'Successfully updated.';
                echo json_encode($output);
                return;
            }
            
            $output['status'] = false;
            $output['message'] = 'Invalid email code.';
            echo json_encode($output);
            return;
        }
        else
        {
            $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
            $table = $_POST['user_type'] == 'Student' ? $STUDENT_TABLE : $USER_TABLE;
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $table SET password = '".$password."', status = 'Active' WHERE id = '".trim($_POST["id"])."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully updated.';
                echo json_encode($output);
                return;
            }
            
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully updated.';
            echo json_encode($output);
            return;
        }
    }

	if($_POST['btn_action'] == 'Sections_add' ) //
    {
        $count = get_total_count($connect, "SELECT * FROM $SECTIONS_TABLE WHERE 
        section = '".trim($_POST["section"])."' AND year_level = '".trim($_POST["year_level"])."' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Section already exist.';
            echo json_encode($output);
            return;
        }
        
        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $SECTIONS_TABLE (section, year_level, status, date_created) VALUES 
        ('".trim($_POST["section"])."', '".trim($_POST["year_level"])."', 'Active', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully added.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully added.';
        echo json_encode($output);
        return;
    }
	
	if($_POST['btn_action'] == 'Sections_fetch' ) //
	{
        $result = fetch_row($connect, "SELECT * FROM $SECTIONS_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['section'] = $result['section'];
        $output['year_level'] = $result['year_level'];
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Sections_status' ) //
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $SECTIONS_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully changed.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully changed.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Sections_update' ) //
	{
        $count = get_total_count($connect, "SELECT * FROM $SECTIONS_TABLE WHERE 
        id != '".$_POST["id"]."' AND section = '".trim($_POST["section"])."' AND year_level = '".trim($_POST["year_level"])."' ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Section already exist.';
            echo json_encode($output);
            return;
        }
        
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $SECTIONS_TABLE SET 
            section = '".trim($_POST["section"])."',
            year_level = '".trim($_POST["year_level"])."' 
        WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully updated.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully updated.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'section_upload_excel' ) //
    {
        if ($_FILES["excel_file"]["name"] !== 'Section Template.xlsx')
        {
            $output['status'] = false;
            $output['message'] = "Invalid File Template!";
            echo json_encode($output);
            return;
        }
        
        $file_array = explode(".", $_FILES["excel_file"]["name"]);  
        if($file_array[1] !== "xlsx")  
        {
            $output['status'] = false;
            $output['message'] = "Invalid File Type!";
            echo json_encode($output);
            return;
        }

        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);  
        include("assets/PHPExcel/Classes/PHPExcel/IOFactory.php"); 
        // PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        $object = PHPExcel_IOFactory::load($_FILES["excel_file"]["tmp_name"]); 
        foreach($object->getWorksheetIterator() as $worksheet)  
        {  
            $highestRow = $worksheet->getHighestRow();  
            for($row=2; $row<=$highestRow; $row++)  //$row=4;
            {   
                $section = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
                $year_level = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());

                $count = get_total_count($connect, "SELECT * FROM $SECTIONS_TABLE WHERE section = '".trim($section)."' AND year_level = '".trim($year_level)."' ");
                if ($count == 0)
                {
                    query($connect, "INSERT INTO $SECTIONS_TABLE (section, year_level, status, date_created) VALUES 
                    ('".trim($section)."', '".trim($year_level)."', 'Active', '".date("m-d-Y h:i A")."') ");
                }
            }  
        }

        $output['status'] = true;
        $output['message'] = "Upload successfully!";
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'Instructors_add' ) //
    {
        $count = get_total_count($connect, "SELECT * FROM $USER_TABLE WHERE email = '".trim($_POST["email"])."' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Email already exist.';
            echo json_encode($output);
            return;
        }
        
        $count = get_total_count($connect, "SELECT * FROM $USER_TABLE WHERE fullname = '".trim($_POST["fullname"])."' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Fullname already exist.';
            echo json_encode($output);
            return;
        }
        
        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $USER_TABLE (fullname, email, user_type, status, date_created) VALUES 
        ('".trim($_POST["fullname"])."', '".trim($_POST["email"])."', 'Staff','Not Activated','".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully added.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully added.';
        echo json_encode($output);
        return;
    }
	
	if($_POST['btn_action'] == 'Instructors_fetch' ) //
	{
        $result = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['fullname'] = $result['fullname'];
        $output['email'] = $result['email'];
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Instructors_status' ) //
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $USER_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully changed.';
            echo json_encode($output);
            return;
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully changed.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Instructors_update' ) //
	{
        $count = get_total_count($connect, "SELECT * FROM $USER_TABLE WHERE id != '".$_POST["id"]."' AND email = '".trim($_POST["email"])."' ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Email already exist.';
            echo json_encode($output);
            return;
        }
        
        $count = get_total_count($connect, "SELECT * FROM $USER_TABLE WHERE id != '".$_POST["id"]."' AND fullname = '".trim($_POST["fullname"])."' ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Fullname already exist.';
            echo json_encode($output);
            return;
        }
        
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $USER_TABLE SET 
            fullname = '".trim($_POST["fullname"])."',
            email = '".trim($_POST["email"])."' 
        WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully updated.';
            echo json_encode($output);
            return;
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully updated.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Rooms_add' ) //
    {
        $count = get_total_count($connect, "SELECT * FROM $ROOMS_TABLE WHERE room = '".trim($_POST["room"])."' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Room already exist.';
            echo json_encode($output);
            return;
        }
        
        $lab_id = "LC-".date('Ym').'0001';
        $result = fetch_row($connect,"SELECT * FROM $ROOMS_TABLE ORDER BY id DESC LIMIT 1 ");
        if ($result)
        {
            if ( date('Ym') == substr($result['lab_id'], 3, 6) )
            {
                $add = intval(substr($result['lab_id'], 9)) + 1;
                if (strlen($add) == 1) { $add = "000".$add; }
                if (strlen($add) == 2) { $add = "00".$add; }
                if (strlen($add) == 3) { $add = "0".$add; }
                $lab_id = "LC-".date('Ym').$add;
            }
        }

        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $ROOMS_TABLE (lab_id, room, seats, status, date_created) VALUES 
        ('".$lab_id."', '".trim($_POST["room"])."', '".trim($_POST["seats"])."', 'Active', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully added.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully added.';
        echo json_encode($output);
        return;
    }
	
	if($_POST['btn_action'] == 'Rooms_fetch' ) //
	{
        $result = fetch_row($connect, "SELECT * FROM $ROOMS_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['lab_id'] = $result['lab_id'];
        $output['room'] = $result['room'];
        $output['seats'] = $result['seats'];
		echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Rooms_status' ) //
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $ROOMS_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully changed.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully changed.';
		echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Rooms_update' ) //
	{
        $count = get_total_count($connect, "SELECT * FROM $ROOMS_TABLE WHERE id != '".$_POST["id"]."' AND room = '".trim($_POST["room"])."' ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Room already exist.';
            echo json_encode($output);
            return;
        }
        
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $ROOMS_TABLE SET 
            room = '".trim($_POST["room"])."',
            seats = '".trim($_POST["seats"])."' 
        WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully updated.';
            echo json_encode($output);
            return;
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully updated.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'scheduled_add' ) //
    {
        $count = get_total_count($connect, "SELECT * FROM $SCHEDULED_TABLE WHERE room_id = '".trim($_POST["room_id"])."' AND days = '".trim($_POST["days"])."' 
        AND times_in = '".trim($_POST["times_in"])."' AND times_out = '".trim($_POST["times_out"])."' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Day, time in and out already exist.';
            echo json_encode($output);
            return;
        }

        //validate teacher section subject day time
        
        $room = fetch_row($connect, "SELECT * FROM $ROOMS_TABLE WHERE id = '".trim($_POST["room_id"])."' ");
        $teacher = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".trim($_POST["teacher_id"])."' ");
        $section = fetch_row($connect, "SELECT * FROM $SECTIONS_TABLE WHERE id = '".trim($_POST["section_id"])."' ");

        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $SCHEDULED_TABLE (lab_id, room_id, room_name, teacher_id, teacher_name, section_id, section_name, year_level, 
        subject, days, times_in, times_out, military_in, military_out, date_created) VALUES 
        ('".$room["lab_id"]."', '".trim($_POST["room_id"])."', '".$room["room"]."', '".trim($_POST["teacher_id"])."', '".$teacher["fullname"]."', 
        '".trim($_POST["section_id"])."', '".$section["section"]."', '".$section["year_level"]."', 
        '".trim($_POST["subject"])."', 
        '".trim($_POST["days"])."', '".trim($_POST["times_in"])."', '".trim($_POST["times_out"])."', 
        '".date('H:i', strtotime(trim($_POST["times_in"])))."', '".date('H:i', strtotime(trim($_POST["times_out"])))."', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully added.';
            echo json_encode($output);
            return;
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully added.';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'scheduled_remove' ) //
    {
        $connect->beginTransaction();
        $create = query($connect, "DELETE FROM $SCHEDULED_TABLE WHERE id = ".$_POST["id"]." ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully removed.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully removed.';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'layout_load' ) //
    {
        $layout = '';
        $room = fetch_row($connect, "SELECT * FROM $ROOMS_TABLE WHERE id = '".$_POST["id"]."' ");
        for ($seats = 1; $seats <= intval($room["seats"]); $seats++) 
        {
            $bg = 'text-success';
            $reports = fetch_row($connect, "SELECT * FROM $REPORTS_TABLE WHERE room_id = '".$_POST["id"]."' AND pc_no = '".$seats."' ORDER BY id DESC ");
            if ($reports)
            {
                if ($reports["pc_status"] !== 'Working')
                {
                    if ($reports["status"] !== 'Solved')
                    {
                        $bg = 'text-danger';
                    }
                }
                // if ($reports["status"] !== 'Solved')
                // {
                //     $bg = 'text-danger';
                // }
            }
            $layout .= '<div class="col-4 col-md-3 rounded border border-secondary p-2 pb-0 text-left pc_no_layout" id="pc_no_'.$seats.'" data-pc_no="'.$seats.'" style="cursor: pointer;">
                            <i class=\'fas fa-circle '.$bg.'\'></i>
                            <label style="border-bottom: 2px solid black; cursor: pointer;" class="p-0 mb-0">PC #'.$seats.'</label>
                        </div>';
        }
        
        $output['status'] = true;
        $output['layout'] = $layout;
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'room_components_add' ) //
    {
        // $count = get_total_count($connect, "SELECT * FROM $ROOM_COMPONENT_TABLE WHERE room_id = '".trim($_POST["room_id"])."' AND pc_no = '".trim($_POST["pc_no"])."' 
        // AND component_id = '".trim($_POST["component_id"])."' ");
        // if ($count > 0)
        // {
        //     $output['status'] = false;
        //     $output['message'] = 'Component already exist.';
        //     echo json_encode($output);
        //     return;
        // }
        
        $room = fetch_row($connect, "SELECT * FROM $ROOMS_TABLE WHERE id = '".trim($_POST["room_id"])."' ");
        $component = fetch_row($connect, "SELECT * FROM $COMPONENTS_TABLE WHERE id = '".trim($_POST["component_id"])."' ");

        $connect->beginTransaction();
        if ($_SESSION["user_type"] == 'Superadmin')
        {
            $create = query($connect, "INSERT INTO $ROOM_COMPONENT_TABLE (room_id, room_name, pc_no, component_id, component_name, component_type, status, date_created) VALUES 
            ('".trim($_POST["room_id"])."', '".$room["room"]."', '".trim($_POST["pc_no"])."', 
            '".trim($_POST["component_id"])."', '".$component["name"]."', '".$component["types"]."', 'Active', '".date("m-d-Y h:i A")."') ");
        }
        else
        {
            $create = query($connect, "INSERT INTO $ROOM_COMPONENT_TABLE (room_id, room_name, pc_no, component_id, component_name, component_type, 
            requested_by, requested_name, requested_date, status, date_created) VALUES 
            ('".trim($_POST["room_id"])."', '".$room["room"]."', '".trim($_POST["pc_no"])."', 
            '".trim($_POST["component_id"])."', '".$component["name"]."', '".$component["types"]."', 
            '".$_SESSION["user_id"]."', '".$_SESSION["fullname"]."', '".date("m-d-Y h:i A")."',
            'Pending', '".date("m-d-Y h:i A")."') ");
        }

        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully added.';
            echo json_encode($output);
            return;
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully added.';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'room_components_remove' ) //
    {
        $connect->beginTransaction();
        $create = query($connect, "DELETE FROM $ROOM_COMPONENT_TABLE WHERE id = ".$_POST["id"]." ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully removed.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully removed.';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'room_components_accept' ) //
    {
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $ROOM_COMPONENT_TABLE SET status = 'Active' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully accepted.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully accepted.';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'Components_add' ) //
    {
        $count = get_total_count($connect, "SELECT * FROM $COMPONENTS_TABLE WHERE name = '".trim($_POST["name"])."' AND types = '".trim($_POST["types"])."' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Component already exist.';
            echo json_encode($output);
            return;
        }

        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $COMPONENTS_TABLE (name, types, status, date_created) VALUES 
        ('".trim($_POST["name"])."', '".trim($_POST["types"])."', 'Active', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully added.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully added.';
        echo json_encode($output);
        return;
    }
	
	if($_POST['btn_action'] == 'Components_fetch' ) //
	{
        $result = fetch_row($connect, "SELECT * FROM $COMPONENTS_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['name'] = $result['name'];
        $output['types'] = $result['types'];
		echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Components_status' ) //
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $COMPONENTS_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully changed.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully changed.';
		echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Components_update' ) //
	{
        $count = get_total_count($connect, "SELECT * FROM $COMPONENTS_TABLE WHERE id != '".$_POST["id"]."' AND 
        name = '".trim($_POST["name"])."' AND types = '".trim($_POST["types"])."' ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Component already exist.';
            echo json_encode($output);
            return;
        }
        
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $COMPONENTS_TABLE SET 
            name = '".trim($_POST["name"])."',
            types = '".trim($_POST["types"])."' 
        WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully updated.';
            echo json_encode($output);
            return;
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully updated.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Students_add' ) //
    {
        $count = get_total_count($connect, "SELECT * FROM $STUDENT_TABLE WHERE student_no = '".trim($_POST["student_no"])."'  ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Student no already exist.';
            echo json_encode($output);
            return;
        }
        
        $count = get_total_count($connect, "SELECT * FROM $STUDENT_TABLE WHERE 
        last_name = '".trim($_POST["last_name"])."' AND 
        first_name = '".trim($_POST["first_name"])."' AND 
        middle_name = '".trim($_POST["middle_name"])."'  ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Student fullname already exist.';
            echo json_encode($output);
            return;
        }
        
        $count = get_total_count($connect, "SELECT * FROM $STUDENT_TABLE WHERE email_address = '".trim($_POST["email_address"])."'  ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Email Address already exist.';
            echo json_encode($output);
            return;
        }
        
        $section = fetch_row($connect, "SELECT * FROM $SECTIONS_TABLE WHERE id = '".trim($_POST["section_id"])."' ");

        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $STUDENT_TABLE (student_no, last_name, first_name, middle_name, email_address, section_id, section, year_level, status, date_created) VALUES 
        ('".trim($_POST["student_no"])."', '".trim($_POST["last_name"])."', '".trim($_POST["first_name"])."', '".trim($_POST["middle_name"])."', 
        '".trim($_POST["email_address"])."', '".trim($_POST["section_id"])."', '".$section["section"]."', '".$section["year_level"]."',
        'Not Activated', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully added.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully added.';
        echo json_encode($output);
        return;
    }
	
	if($_POST['btn_action'] == 'Students_fetch' ) // 
	{
        $result = fetch_row($connect, "SELECT * FROM $STUDENT_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['student_no'] = $result['student_no'];
        $output['last_name'] = $result['last_name'];
        $output['first_name'] = $result['first_name'];
        $output['middle_name'] = $result['middle_name'];
        $output['email_address'] = $result['email_address'];
        $output['section_id'] = $result['section_id'];
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Students_status' ) //
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $STUDENT_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully changed.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully changed.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Students_update' ) //
	{
        $count = get_total_count($connect, "SELECT * FROM $STUDENT_TABLE WHERE id != '".$_POST["id"]."' AND student_no = '".trim($_POST["student_no"])."'  ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Student no already exist.';
            echo json_encode($output);
            return;
        }
        
        $count = get_total_count($connect, "SELECT * FROM $STUDENT_TABLE WHERE id != '".$_POST["id"]."' AND last_name = '".trim($_POST["last_name"])."' AND first_name = '".trim($_POST["first_name"])."' AND middle_name = '".trim($_POST["middle_name"])."'  ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Student fullname already exist.';
            echo json_encode($output);
            return;
        }
        
        $count = get_total_count($connect, "SELECT * FROM $STUDENT_TABLE WHERE id != '".$_POST["id"]."' AND email_address = '".trim($_POST["email_address"])."'  ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Email Address already exist.';
            echo json_encode($output);
            return;
        }
        
        $section = fetch_row($connect, "SELECT * FROM $SECTIONS_TABLE WHERE id = '".trim($_POST["section_id"])."' ");

        $connect->beginTransaction();
        $update = query($connect, "UPDATE $STUDENT_TABLE SET 
            student_no = '".trim($_POST["student_no"])."',
            last_name = '".trim($_POST["last_name"])."',
            first_name = '".trim($_POST["first_name"])."',
            middle_name = '".trim($_POST["middle_name"])."',
            email_address = '".trim($_POST["email_address"])."',
            section_id = '".trim($_POST["section_id"])."',
            section = '".$section["section"]."',
            year_level = '".$section["year_level"]."'
        WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully updated.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully updated.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'student_upload_excel' ) //
    {
        if ($_FILES["excel_file"]["name"] !== 'Student Template.xlsx')
        {
            $output['status'] = false;
            $output['message'] = "Invalid File Template!";
            echo json_encode($output);
        }
        else
        {
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);  
            $file_array = explode(".", $_FILES["excel_file"]["name"]);  
            if($file_array[1] == "xlsx")  
            {
                include("assets/PHPExcel/Classes/PHPExcel/IOFactory.php"); 
                // PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
                $object = PHPExcel_IOFactory::load($_FILES["excel_file"]["tmp_name"]); 
                foreach($object->getWorksheetIterator() as $worksheet)  
                {  
                    $highestRow = $worksheet->getHighestRow();  
                    for($row=2; $row<=$highestRow; $row++)  //$row=4;
                    {   
                        $student_no = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
                        $last_name = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
                        $first_name = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
                        $middle_name = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
                        $email_address = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(4, $row)->getValue());
                        $section_id = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(5, $row)->getValue());

                        if (!empty($student_no))
                        {
                            $count = get_total_count($connect, "SELECT * FROM $STUDENT_TABLE WHERE student_no = '".trim($student_no)."' AND last_name = '".trim($last_name)."' 
                            AND first_name = '".trim($first_name)."' AND middle_name = '".trim($middle_name)."' ");
                            if ($count == 0)
                            {
                                $section = fetch_row($connect, "SELECT * FROM $SECTIONS_TABLE WHERE id = '".$section_id."' ");
    
                                query($connect, "INSERT INTO $STUDENT_TABLE 
                                    (student_no, last_name, first_name, middle_name, email_address, section_id, section, year_level, status, date_created) 
                                VALUES 
                                    ('".trim($student_no)."', '".trim($last_name)."', '".trim($first_name)."', '".trim($middle_name)."',
                                    '".trim($email_address)."', '".trim($section_id)."', '".$section["section"]."', '".$section["year_level"]."', 'Not Activated', '".date("m-d-Y h:i A")."') ");
                            }
                        }
    
                    }  
                }
                $output['status'] = true;
                $output['message'] = "Upload successfully!";
                echo json_encode($output);
            }
            else 
            {
                $output['status'] = false;
                $output['message'] = "Invalid File Type!";
                echo json_encode($output);
            }
        }
    }

	if($_POST['btn_action'] == 'students_load' ) //
    {
        $students = '<option value="">Select Student</option>';
        $scheduled = fetch_row($connect, "SELECT * FROM $SCHEDULED_TABLE WHERE id = '".$_POST["scheduled_id"]."' ");
        // $students = '<select name="student_id" id="student_id" class="form-control " required >
        //                 <option value="">Select Student</option>';
        $result = fetch_all($connect,"SELECT * FROM $STUDENT_TABLE WHERE section_id = '".$scheduled["section_id"]."' AND status != 'Inactive' " );  
        // AND year_level = '".$scheduled["year_level"]."' AND id NOT IN (SELECT student_id FROM $SCHEDULED_STUDENTS_TABLE )
        foreach($result as $row)
        {
            $assigned = fetch_row($connect, "SELECT * FROM $SCHEDULED_STUDENTS_TABLE WHERE scheduled_id = '".$_POST["scheduled_id"]."' 
            AND pc_no = '".$_POST["pc_no"]."' AND student_id = '".$row["id"]."' ");
            if ($assigned)
            {
                $students .= '<option selected value="'.$row["id"].'">'.($row["last_name"].", ".$row["first_name"]." ".$row["middle_name"]).'</option>';
            }
            else
            {
                $students .= '<option value="'.$row["id"].'">'.($row["last_name"].", ".$row["first_name"]." ".$row["middle_name"]).'</option>';
            }
        }
        // $students .= '</select>';
        $output['students'] = $students;
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'room_student_assign' ) //
    {
        $result = fetch_row($connect, "SELECT * FROM $SCHEDULED_STUDENTS_TABLE WHERE scheduled_id = '".$_POST["scheduled_id"]."' 
        AND student_id = '".$_POST["student_id"]."'  ");
        if ($result)
        {
            $output['status'] = false;
            $output['message'] = 'Student already assigned.';
            echo json_encode($output);
            return;
        }

        $student = fetch_row($connect, "SELECT * FROM $STUDENT_TABLE WHERE id = '".$_POST["student_id"]."' ");
        $assigned = fetch_row($connect, "SELECT * FROM $SCHEDULED_STUDENTS_TABLE WHERE scheduled_id = '".$_POST["scheduled_id"]."' 
        AND pc_no = '".$_POST["pc_no"]."'  ");
        if ($assigned)
        {
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $SCHEDULED_STUDENTS_TABLE SET 
                student_id = '".$_POST["student_id"]."',
                student_name = '".($student["last_name"].", ".$student["first_name"]." ".$student["middle_name"])."'
            WHERE id = '".$assigned['id']."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully assigned.';
                echo json_encode($output);
                return;
            }
            
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully assigned.';
            echo json_encode($output);
            return;
        }
        else
        {
            $scheduled = fetch_row($connect, "SELECT * FROM $SCHEDULED_TABLE WHERE id = '".$_POST["scheduled_id"]."' ");

            $connect->beginTransaction();
            $create = query($connect, "INSERT INTO $SCHEDULED_STUDENTS_TABLE (scheduled_id, pc_no, 
            student_id, student_name,
            lab_id, room_id, room_name, 
            teacher_id, teacher_name, 
            section_id, section_name, year_level, 
            date_created) VALUES 
            ('".trim($_POST["scheduled_id"])."', '".trim($_POST["pc_no"])."', 
            '".trim($_POST["student_id"])."', '".($student["last_name"].", ".$student["first_name"]." ".$student["middle_name"])."', 
            '".$scheduled["lab_id"]."', '".$scheduled["room_id"]."', '".$scheduled["room_name"]."', 
            '".$scheduled["teacher_id"]."', '".$scheduled["teacher_name"]."', 
            '".$scheduled["section_id"]."', '".$scheduled["section_name"]."', '".$scheduled["year_level"]."', 
            '".date("m-d-Y h:i A")."') ");
            if ($create == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully assigned.';
                echo json_encode($output);
                return;
            }
            
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully assigned.';
            echo json_encode($output);
            return;
        }
    }

	if($_POST['btn_action'] == 'Reports Category_add' ) //
    {
        $count = get_total_count($connect, "SELECT * FROM $CATEGORY_TABLE WHERE category = '".trim($_POST["category"])."' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Category already exist.';
            echo json_encode($output);
            return;
        }
        
        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $CATEGORY_TABLE (category, status, date_created) VALUES 
        ('".trim($_POST["category"])."', 'Active','".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully added.';
            echo json_encode($output);
            return;
        }
        
        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully added.';
        echo json_encode($output);
        return;
    }
	
	if($_POST['btn_action'] == 'Reports Category_fetch' ) //
	{
        $result = fetch_row($connect, "SELECT * FROM $CATEGORY_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['category'] = $result['category'];
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Reports Category_status' ) //
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $CATEGORY_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully changed.';
            echo json_encode($output);
            return;
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully changed.';
        echo json_encode($output);
        return;
	}

	if($_POST['btn_action'] == 'Reports Category_update' ) //
	{
        $count = get_total_count($connect, "SELECT * FROM $CATEGORY_TABLE WHERE id != '".$_POST["id"]."' AND category = '".trim($_POST["category"])."' ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Category already exist.';
            echo json_encode($output);
            return;
        }
        
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $CATEGORY_TABLE SET 
            category = '".trim($_POST["category"])."' 
        WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully updated.';
            echo json_encode($output);
            return;
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully updated.';
        echo json_encode($output);
        return;
	}
	
	if($_POST['btn_action'] == 'scanner' ) //
    {
        $scheduled_student = fetch_row($connect, "SELECT * FROM $SCHEDULED_STUDENTS_TABLE 
        WHERE lab_id = '".trim($_POST["lab_id"])."' AND student_id = '".trim($_POST["student_id"])."' ");
        if (!$scheduled_student)
        {
            $output['status'] = false;
            $output['message'] = 'No data found.';
            echo json_encode($output);
            return;
        }

        // $scheduled = fetch_row($connect, "SELECT * FROM $SCHEDULED_TABLE WHERE id = '".$scheduled_student["scheduled_id"]."'
        // AND days = '".date('l')."' AND '".date('H:i')."' BETWEEN military_in AND military_out ");
        $scheduled = fetch_row($connect, "SELECT * FROM $SCHEDULED_TABLE WHERE id IN (SELECT scheduled_id FROM $SCHEDULED_STUDENTS_TABLE 
        WHERE lab_id = '".trim($_POST["lab_id"])."' AND student_id = '".trim($_POST["student_id"])."' )
        AND days = '".date('l')."' AND '".date('H:i')."' BETWEEN military_in AND military_out ");
        if (!$scheduled)
        {
            $output['status'] = false;
            $output['message'] = 'No scheduled found for today\'s day and time.';
            echo json_encode($output);
            return;
        }

        // $output['days'] = $scheduled["days"];
        // $output['times_in'] = $scheduled["times_in"];
        // $output['times_out'] = $scheduled["times_out"];

        $output['subject'] = $scheduled["subject"];
        $output['day'] = $scheduled["days"]." (".$scheduled["times_in"]." - ".$scheduled["times_out"].")";
        $output['pc_no'] = "PC #: ".$scheduled_student["pc_no"];
        $output['room_name'] = "Room: ".$scheduled_student["room_name"];
        $output['section_name'] = "Section: ".$scheduled_student["year_level"]." - ".$scheduled_student["section_name"];
        $output['teacher_name'] = "Instructor: ".$scheduled_student["teacher_name"];
        $output['scheduled_student_id'] = $scheduled_student["id"];

        $output['pc_status'] = "PC Status: Working";
        $reports = fetch_row($connect, "SELECT * FROM $REPORTS_TABLE WHERE room_id = '".$scheduled_student["room_id"]."' AND pc_no = '".$scheduled_student["pc_no"]."' ORDER BY id DESC LIMIT 1 ");
        if ($reports)
        {
            $output['pc_status'] = "PC Status: ".$reports["pc_status"];
        }

        $output['status'] = true;
        echo json_encode($output);
        return;
    }
	
	if($_POST['btn_action'] == 'reports_file' ) //
    {
        // report valdiation
        if ($_FILES["files"]["size"] == 0)
        {
            $output['status'] = false;
            $output['message'] = 'Please upload an image.';
            echo json_encode($output);
            return;
        }
        $images = '';
        $user_type = 'Student';
        if (isset($_SESSION["user_type"]))
        {
            $user_type = $_SESSION["user_type"] == 'Staff' ? 'Staff' : 'Student';
        }
        
        $connect->beginTransaction();
        if ($user_type == 'Student')
        {
            // student
            $scheduled = fetch_row($connect, "SELECT * FROM $SCHEDULED_STUDENTS_TABLE WHERE id = '".trim($_POST["scheduled_student_id"])."' ");
            $image = $_FILES["files"]["name"];
            $png = strpos($image, 'png');
            $jpg = strpos($image, 'jpg');
            $jpeg = strpos($image, 'jpeg');
            $type = $png !== false ? 'png' : ($jpg !== false ? 'jpg' : ($jpeg !== false ? 'jpeg' : 'false' ));
            if ($type == 'false')
            {
                $output['status'] = false;
                $output['message'] = "Invalid file type, please upload a png, jpg or jpeg.";
                echo json_encode($output);
                return;
            }
            $file_type = array("jpg", "png", "jpeg");
            $upload = upload_image($_FILES["files"], $scheduled["room_id"].'_'.$scheduled["pc_no"].'_'.trim($_POST["category"]).'_'.date("mdY_hiA"), 'assets/reports/', $file_type, $type);
            if ($upload["status"] == false)
            {
                $output['status'] = false;
                $output['message'] = $upload["message"];
                echo json_encode($output);
                return;
            }
            $images = $upload["message"];
            
            $create = query($connect, "INSERT INTO $REPORTS_TABLE (type, image, category, pc_status, issue, 
            scheduled_id, pc_no, student_id, student_name,
            lab_id, room_id, room_name, teacher_id, teacher_name, section_id, section_name, year_level,
            status, date_created) VALUES 
            ('".$user_type."', '".$images."', '".trim($_POST["category"])."', '".trim($_POST["pc_status"])."', '".trim($_POST["issue"])."', 
            '".trim($_POST["scheduled_student_id"])."', '".$scheduled["pc_no"]."', '".$scheduled["student_id"]."', '".$scheduled["student_name"]."',
            '".$scheduled["lab_id"]."', '".$scheduled["room_id"]."', '".$scheduled["room_name"]."', '".$scheduled["teacher_id"]."', '".$scheduled["teacher_name"]."',
            '".$scheduled["section_id"]."', '".$scheduled["section_name"]."', '".$scheduled["year_level"]."',
            'Pending', '".date("m-d-Y h:i A")."') ");
        }
        else // staff
        {
            $scheduled = fetch_row($connect, "SELECT * FROM $SCHEDULED_TABLE WHERE id = '".trim($_POST["scheduled_student_id"])."' ");
            $image = $_FILES["files"]["name"];
            $png = strpos($image, 'png');
            $jpg = strpos($image, 'jpg');
            $jpeg = strpos($image, 'jpeg');
            $type = $png !== false ? 'png' : ($jpg !== false ? 'jpg' : ($jpeg !== false ? 'jpeg' : 'false' ));
            if ($type == 'false')
            {
                $output['status'] = false;
                $output['message'] = "Invalid file type, please upload a png, jpg or jpeg.";
                echo json_encode($output);
                return;
            }
            $file_type = array("jpg", "png", "jpeg");
            $upload = upload_image($_FILES["files"], $scheduled["room_id"].'_'.trim($_POST["pc_no"]).'_'.trim($_POST["category"]).'_'.date("mdY_hiA"), 'assets/reports/', $file_type, $type);
            if ($upload["status"] == false)
            {
                $output['status'] = false;
                $output['message'] = $upload["message"];
                echo json_encode($output);
                return;
            }
            $images = $upload["message"];
            
            $student_id = '';
            $student_name = '';
            $students = fetch_row($connect, "SELECT * FROM $SCHEDULED_STUDENTS_TABLE WHERE scheduled_id = '".trim($_POST["scheduled_student_id"])."' AND pc_no = '".trim($_POST["pc_no"])."' ");
            if ($students)
            {
                $student_id = $students["student_id"];
                $student_name = $students["student_name"];
            }
            $create = query($connect, "INSERT INTO $REPORTS_TABLE (type, image, category, pc_status, issue, 
            scheduled_id, pc_no, student_id, student_name,
            lab_id, room_id, room_name, teacher_id, teacher_name, section_id, section_name, year_level,
            status, date_verified, date_created) VALUES 
            ('".$user_type."', '".$images."', '".trim($_POST["category"])."', '".trim($_POST["pc_status"])."', '".trim($_POST["issue"])."', 
            '".trim($_POST["scheduled_student_id"])."', '".trim($_POST["pc_no"])."', '".$student_id."', '".$student_name."',
            '".$scheduled["lab_id"]."', '".$scheduled["room_id"]."', '".$scheduled["room_name"]."', '".$scheduled["teacher_id"]."', '".$scheduled["teacher_name"]."',
            '".$scheduled["section_id"]."', '".$scheduled["section_name"]."', '".$scheduled["year_level"]."',
            'Ongoing', '".date("m-d-Y h:i A")."', '".date("m-d-Y h:i A")."') ");
        }

        if ($create == true)
        {   
            if ($user_type == 'Student')
            {
                // send email to instructor
                $teacher = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$scheduled["teacher_id"]."' "); // $teacher["email"]
                $mail = send_mail($teacher["email"], 
                    $teacher['fullname'], 
                    'Laboratory Report', 
                    'Good day, 
                    <br><br>'.$scheduled["student_name"].' has submitted a laboratory report.
                    <br>
                    <b>DETAILS</b>
                    <br><b>Room:</b> '.$scheduled["room_name"].'
                    <br><b>Section:</b> '.$scheduled["section_name"].'
                    <br><b>PC #:</b> '.$scheduled["pc_no"].'
                    <br><b>Category:</b> '.trim($_POST["category"]).'
                    <br><b>PC Status:</b> '.trim($_POST["pc_status"]).'
                    <br><b>Issue:</b> '.trim($_POST["issue"]).'
                    <br><br> 
                    Cavite State University 
                    <br><br> 
                    <i>This is a system generated email. Do not reply.<i>');
            }
            else
            {
                // send email to admin
                $teacher = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '1' "); // $teacher["email"]
                $mail = send_mail($teacher["email"], 
                    'Administrator', 
                    'Laboratory Report', 
                    'Good day, 
                    <br><br>'.$scheduled["teacher_name"].' has submitted a laboratory report.
                    <br>
                    <b>DETAILS</b>
                    <br><b>Room:</b> '.$scheduled["room_name"].'
                    <br><b>Section:</b> '.$scheduled["section_name"].'
                    <br><b>PC #:</b> '.trim($_POST["pc_no"]).'
                    <br><b>Category:</b> '.trim($_POST["category"]).'
                    <br><b>PC Status:</b> '.trim($_POST["pc_status"]).'
                    <br><b>Issue:</b> '.trim($_POST["issue"]).'
                    <br><br> 
                    Cavite State University 
                    <br><br> 
                    <i>This is a system generated email. Do not reply.<i>');
            }
            if ($mail)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully submitted.';
                echo json_encode($output);
                return;
            }
        }

        $output['status'] = true;
        $output['message'] = 'Unsuccessfully submitted.';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'Reports_status' ) //
    {
        $status = $_POST['status'];
        $query = '';
        if ($_POST['status'] == 'Verified')
        {
            $status = 'Ongoing';
            $query = ", date_verified = '".date("m-d-Y h:i A")."' ";
        }
        else if ($_POST['status'] == 'Not Verified')
        {
            $query = ", action_taken = '".trim($_POST['action_taken'])."', date_verified = '".date("m-d-Y h:i A")."' ";
        }
        else if ($_POST['status'] !== 'Archived')
        {
            $query = ", action_taken = '".trim($_POST['action_taken'])."', date_solved = '".date("m-d-Y h:i A")."' ";
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $REPORTS_TABLE SET status = '".$status."' $query WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $reports = fetch_row($connect, "SELECT * FROM $REPORTS_TABLE WHERE id = '".$_POST['id']."' ");
            if ($_POST['status'] == 'Verified')
            {
                // send email to admin
                $teacher = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '1' "); // $teacher["email"] $teacher['fullname']
                $mail = send_mail($teacher["email"], 
                    'Administrator', 
                    'Laboratory Report', 
                    'Good day, 
                    <br><br>A laboratory report has been verified.
                    <br>
                    <b>DETAILS</b>
                    <br><b>Submitted By:</b> '.$reports["student_name"].'
                    <br><b>Submitted Date:</b> '.$reports["date_created"].'
                    <br><b>Room:</b> '.$reports["room_name"].'
                    <br><b>Section:</b> '.$reports["section_name"].'
                    <br><b>PC #:</b> '.$reports["pc_no"].'
                    <br><b>Category:</b> '.$reports["category"].'
                    <br><b>PC Status:</b> '.$reports["pc_status"].'
                    <br><b>Issue:</b> '.$reports["issue"].'
                    <br><br> 
                    Cavite State University 
                    <br><br> 
                    <i>This is a system generated email. Do not reply.<i>');
                if ($mail)
                {
                    $connect->commit();
                    $output['status'] = true;
                    $output['message'] = 'Successfully '.strtolower($_POST['status']).'.';
                    echo json_encode($output);
                    return;
                }
            }
            else if ($_POST['status'] == 'Not Verified')
            {
                // send email to admin // send email to student
                $student = fetch_row($connect, "SELECT * FROM $STUDENT_TABLE WHERE id = '".$reports["student_id"]."' "); 
                $mail = send_mail($student["email_address"], 
                    $reports['student_name'], 
                    'Laboratory Report', 
                    'Good day, 
                    <br><br>Your laboratory report has been not verified.
                    <br>
                    <b>DETAILS</b>
                    <br><b>Submitted Date:</b> '.$reports["date_created"].'
                    <br><b>Room:</b> '.$reports["room_name"].'
                    <br><b>Section:</b> '.$reports["section_name"].'
                    <br><b>PC #:</b> '.$reports["pc_no"].'
                    <br><b>Category:</b> '.$reports["category"].'
                    <br><b>PC Status:</b> '.$reports["pc_status"].'
                    <br><b>Issue:</b> '.$reports["issue"].'
                    <br><b>Remarks:</b> '.trim($_POST['action_taken']).'
                    <br><br> 
                    Cavite State University 
                    <br><br> 
                    <i>This is a system generated email. Do not reply.<i>');
                if ($mail)
                {
                    $connect->commit();
                    $output['status'] = true;
                    $output['message'] = 'Successfully '.strtolower($_POST['status']).'.';
                    echo json_encode($output);
                    return;
                }
            }
            else if ($_POST['status'] !== 'Archived')
            {
                if ($reports["type"] == 'Student')
                {
                    // send email to student
                    $student = fetch_row($connect, "SELECT * FROM $STUDENT_TABLE WHERE id = '".$reports["student_id"]."' "); 
                    $mail = send_mail($student["email_address"], 
                        $reports['student_name'], 
                        'Laboratory Report', 
                        'Good day, 
                        <br><br>Your laboratory report has been solved.
                        <br>
                        <b>DETAILS</b>
                        <br><b>Submitted Date:</b> '.$reports["date_created"].'
                        <br><b>Room:</b> '.$reports["room_name"].'
                        <br><b>Section:</b> '.$reports["section_name"].'
                        <br><b>PC #:</b> '.$reports["pc_no"].'
                        <br><b>Category:</b> '.$reports["category"].'
                        <br><b>PC Status:</b> '.$reports["pc_status"].'
                        <br><b>Issue:</b> '.$reports["issue"].'
                        <br><b>Verified By:</b> '.$reports["teacher_name"].'
                        <br><b>Verified Date:</b> '.$reports["date_verified"].'
                        <br><b>Action Taken:</b> '.trim($_POST['action_taken']).'
                        <br><br> 
                        Cavite State University 
                        <br><br> 
                        <i>This is a system generated email. Do not reply.<i>');
                }
                else
                {
                    // send email to teacher
                    $teacher = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$reports["teacher_id"]."' "); 
                    $mail = send_mail($teacher["email"], 
                        $reports['teacher_name'], 
                        'Laboratory Report', 
                        'Good day, 
                        <br><br>Your laboratory report has been solved.
                        <br>
                        <b>DETAILS</b>
                        <br><b>Submitted Date:</b> '.$reports["date_created"].'
                        <br><b>Room:</b> '.$reports["room_name"].'
                        <br><b>Section:</b> '.$reports["section_name"].'
                        <br><b>PC #:</b> '.$reports["pc_no"].'
                        <br><b>Category:</b> '.$reports["category"].'
                        <br><b>PC Status:</b> '.$reports["pc_status"].'
                        <br><b>Issue:</b> '.$reports["issue"].'
                        <br><b>Action Taken:</b> '.trim($_POST['action_taken']).'
                        <br><br> 
                        Cavite State University 
                        <br><br> 
                        <i>This is a system generated email. Do not reply.<i>');
                }
                if ($mail)
                {
                    $connect->commit();
                    $output['status'] = true;
                    $output['message'] = 'Successfully '.strtolower($_POST['status']).'.';
                    echo json_encode($output);
                    return;
                }
            }
            else
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully '.strtolower($_POST['status']).'.';
                echo json_encode($output);
                return;
            }
        }

        $connect->rollBack();
        $output['status'] = false;
        $output['message'] = 'Unsuccessfully '.strtolower($_POST['status']).'.';
        echo json_encode($output);
        return;
    }

	if($_POST['btn_action'] == 'scheduled_upload_excel' ) //
    {
        if ($_FILES["excel_file"]["name"] !== 'Schedule Template.xlsx')
        {
            $output['status'] = false;
            $output['message'] = "Invalid File Template!";
            echo json_encode($output);
        }
        else
        {
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);  
            $file_array = explode(".", $_FILES["excel_file"]["name"]);  
            if($file_array[1] == "xlsx")  
            {
                include("assets/PHPExcel/Classes/PHPExcel/IOFactory.php"); 
                // PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
                $object = PHPExcel_IOFactory::load($_FILES["excel_file"]["tmp_name"]); 
                foreach($object->getWorksheetIterator() as $worksheet)  
                {  
                    $highestRow = $worksheet->getHighestRow();  
                    for($row=2; $row<=$highestRow; $row++)  //$row=4;
                    {   
                        $lab_id = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
                        $teacher_id = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
                        $section_id = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
                        $subject = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
                        $day = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(4, $row)->getValue());
                        $time_in = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(5, $row)->getValue());
                        $time_out = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(6, $row)->getValue());

                        if (!empty($lab_id))
                        {
                            $rooms = fetch_row($connect, "SELECT * FROM $ROOMS_TABLE WHERE lab_id = '".$lab_id."' ");
                            $teacher = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$teacher_id."' ");
                            $section = fetch_row($connect, "SELECT * FROM $SECTIONS_TABLE WHERE id = '".$section_id."' ");

                            query($connect, "INSERT INTO $SCHEDULED_TABLE 
                                (lab_id, room_id, room_name, teacher_id, teacher_name, section_id, section_name, year_level, subject, days, 
                                times_in, times_out, military_in, military_out, date_created) 
                            VALUES 
                                ('".trim($lab_id)."', '".$rooms["id"]."', '".$rooms["room"]."', '".trim($teacher_id)."', '".$teacher["fullname"]."', 
                                '".trim($section_id)."', '".$section["section"]."', '".$section["year_level"]."', 
                                '".trim($subject)."', '".trim($day)."', 
                                '".trim($time_in)."', '".trim($time_out)."',
                                '".date('H:i', strtotime(trim($time_in)))."', '".date('H:i', strtotime(trim($time_out)))."', 
                                '".date("m-d-Y h:i A")."') "); // date('H:i', strtotime(trim($_POST["times_in"])))
                        }
    
                    }  
                }
                $output['status'] = true;
                $output['message'] = "Upload successfully!";
                echo json_encode($output);
            }
            else 
            {
                $output['status'] = false;
                $output['message'] = "Invalid File Type!";
                echo json_encode($output);
            }
        }
    }

	if($_POST['btn_action'] == 'print' ) // (?)
    {
        $_SESSION['first'] = $_POST['first'];
        $_SESSION['second'] = $_POST['second'];
        $output['status'] = false;
		echo json_encode($output);
    }

}

?>