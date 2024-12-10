<?php
    
    try{

        define('DB_HOST','localhost');
        define('DB_USER','root');
        define('DB_PASS','');
        define('DB_NAME','fuma');
        date_default_timezone_set('Asia/Manila');
        
        $conn_pdo = new PDO("mysql:host=".DB_HOST, DB_USER, DB_PASS);
        // set the PDO error mode to exception
        $conn_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn_pdo->query("CREATE DATABASE IF NOT EXISTS ".DB_NAME);

        $connect = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS); 
        $USER_TABLE = 'user_account';
        $USER_COLUMN = 'fullname, email, password, contact, address, user_type, status, date_created';
        session_start();
        
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        include('function.php');

        $query = "SHOW TABLES LIKE '$USER_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $USER_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `fullname` VARCHAR(255) DEFAULT NULL,
                `email` VARCHAR(255) DEFAULT NULL,
                `email_code` VARCHAR(255) DEFAULT NULL,
                `password` VARCHAR(255) DEFAULT NULL,
                `contact` VARCHAR(255) DEFAULT NULL,
                `address` VARCHAR(255) DEFAULT NULL,
                `user_type` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
            $password = password_hash('School', PASSWORD_DEFAULT);
            query($connect, "INSERT INTO $USER_TABLE ($USER_COLUMN) VALUES ('School', 'johnmar.diaz@cvsu.edu.ph', '".$password."' , '', '', 'Superadmin', 'Active','".date("m-d-Y h:i A")."') ");
        }

        // $SY_TABLE = 'school_year';
        // $query = "SHOW TABLES LIKE '$SY_TABLE'";
        // $statement = $connect->prepare($query);
        // $statement->execute();
        // if ($statement->rowCount() == 0)
        // {
        //     $create = "CREATE TABLE $SY_TABLE(
        //         `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        //         `school_year` VARCHAR(255) DEFAULT NULL,
        //         `status` VARCHAR(255) DEFAULT NULL,
        //         `date_created` VARCHAR(255) DEFAULT NULL,
        //         INDEX (`id`)
        //     );";
        //     $connect->exec($create);
        // }

        // $LCS_TABLE = 'lab_components_software';
        // $query = "SHOW TABLES LIKE '$LCS_TABLE'";
        // $statement = $connect->prepare($query);
        // $statement->execute();
        // if ($statement->rowCount() == 0)
        // {
        //     $create = "CREATE TABLE $LCS_TABLE(
        //         `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        //         `lab_id` VARCHAR(255) DEFAULT NULL,
        //         `teacher_id` VARCHAR(255) DEFAULT NULL,
        //         `section` VARCHAR(255) DEFAULT NULL,
        //         `lab_student_id` VARCHAR(255) DEFAULT NULL,
        //         `name` VARCHAR(255) DEFAULT NULL,
        //         `types` VARCHAR(255) DEFAULT NULL,
        //         `status` VARCHAR(255) DEFAULT NULL,
        //         `reason` VARCHAR(255) DEFAULT NULL,
        //         `date_created` VARCHAR(255) DEFAULT NULL,
        //         INDEX (`id`)
        //     );";
        //     $connect->exec($create);
        // }

        // $LS_TABLE = 'lab_scheduled';
        // $query = "SHOW TABLES LIKE '$LS_TABLE'";
        // $statement = $connect->prepare($query);
        // $statement->execute();
        // if ($statement->rowCount() == 0)
        // {
        //     $create = "CREATE TABLE $LS_TABLE(
        //         `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        //         `lab_id` VARCHAR(255) DEFAULT NULL,
        //         `teacher_id` VARCHAR(255) DEFAULT NULL,
        //         `teacher` VARCHAR(255) DEFAULT NULL,
        //         `subject` VARCHAR(255) DEFAULT NULL,
        //         `section` VARCHAR(255) DEFAULT NULL,
        //         `days` VARCHAR(255) DEFAULT NULL,
        //         `times_in` VARCHAR(255) DEFAULT NULL,
        //         `times_out` VARCHAR(255) DEFAULT NULL,
        //         `military_in` VARCHAR(255) DEFAULT NULL,
        //         `military_out` VARCHAR(255) DEFAULT NULL,
        //         `date_created` VARCHAR(255) DEFAULT NULL,
        //         INDEX (`id`)
        //     );";
        //     $connect->exec($create);
        // }

        // $LL_TABLE = 'lab_layout';
        // $query = "SHOW TABLES LIKE '$LL_TABLE'";
        // $statement = $connect->prepare($query);
        // $statement->execute();
        // if ($statement->rowCount() == 0)
        // {
        //     $create = "CREATE TABLE $LL_TABLE(
        //         `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        //         `lab_id` VARCHAR(255) DEFAULT NULL,
        //         `image` VARCHAR(255) DEFAULT NULL,
        //         `lab_no` VARCHAR(255) DEFAULT NULL,
        //         `students_no` VARCHAR(255) DEFAULT NULL,
        //         `status` VARCHAR(255) DEFAULT NULL,
        //         `date_created` VARCHAR(255) DEFAULT NULL,
        //         INDEX (`id`)
        //     );";
        //     $connect->exec($create);
        // }

        // $LAB_STUDENT_TABLE = 'lab_student';
        // $query = "SHOW TABLES LIKE '$LAB_STUDENT_TABLE'";
        // $statement = $connect->prepare($query);
        // $statement->execute();
        // if ($statement->rowCount() == 0)
        // {
        //     $create = "CREATE TABLE $LAB_STUDENT_TABLE(
        //         `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        //         `lab_id` VARCHAR(255) DEFAULT NULL,
        //         `seat_no` VARCHAR(255) DEFAULT NULL,
        //         `student_no` VARCHAR(255) DEFAULT NULL,
        //         `teacher_id` VARCHAR(255) DEFAULT NULL,
        //         `section` VARCHAR(255) DEFAULT NULL,
        //         `status` VARCHAR(255) DEFAULT NULL,
        //         `remarks` VARCHAR(255) DEFAULT NULL,
        //         `date_created` VARCHAR(255) DEFAULT NULL,
        //         INDEX (`id`)
        //     );";
        //     $connect->exec($create);
        // }

        $STUDENT_TABLE = 'student'; //
        $query = "SHOW TABLES LIKE '$STUDENT_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $STUDENT_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `student_no` VARCHAR(255) DEFAULT NULL,
                `last_name` VARCHAR(255) DEFAULT NULL,
                `first_name` VARCHAR(255) DEFAULT NULL,
                `middle_name` VARCHAR(255) DEFAULT NULL,
                `email_address` VARCHAR(255) DEFAULT NULL,
                `email_code` VARCHAR(255) DEFAULT NULL,
                `password` VARCHAR(255) DEFAULT NULL,
                `section_id` VARCHAR(255) DEFAULT NULL,
                `section` VARCHAR(255) DEFAULT NULL,
                `year_level` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        // $LAB_REPORT_TABLE = 'lab_report';
        // $query = "SHOW TABLES LIKE '$LAB_REPORT_TABLE'";
        // $statement = $connect->prepare($query);
        // $statement->execute();
        // if ($statement->rowCount() == 0)
        // {
        //     $create = "CREATE TABLE $LAB_REPORT_TABLE(
        //         `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        //         `category_type` VARCHAR(255) DEFAULT NULL,
        //         `lab_id` VARCHAR(255) DEFAULT NULL,
        //         `seat_no` VARCHAR(255) DEFAULT NULL,
        //         `student_no` VARCHAR(255) DEFAULT NULL,
        //         `report` VARCHAR(255) DEFAULT NULL,
        //         `teacher_id` VARCHAR(255) DEFAULT NULL,
        //         `teacher` VARCHAR(255) DEFAULT NULL,
        //         `section` VARCHAR(255) DEFAULT NULL,
        //         `status` VARCHAR(255) DEFAULT NULL,
        //         `remarks` VARCHAR(255) DEFAULT NULL,
        //         `date_created` VARCHAR(255) DEFAULT NULL,
        //         INDEX (`id`)
        //     );";
        //     $connect->exec($create);
        // }

        // $LOGS_TABLE = 'computer_logs';
        // $query = "SHOW TABLES LIKE '$LOGS_TABLE'";
        // $statement = $connect->prepare($query);
        // $statement->execute();
        // if ($statement->rowCount() == 0)
        // {
        //     $create = "CREATE TABLE $LOGS_TABLE(
        //         `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        //         `lab_id` VARCHAR(255) DEFAULT NULL,
        //         `seat_no` VARCHAR(255) DEFAULT NULL,
        //         `student_no` VARCHAR(255) DEFAULT NULL,
        //         `teacher_id` VARCHAR(255) DEFAULT NULL,
        //         `teacher` VARCHAR(255) DEFAULT NULL,
        //         `section` VARCHAR(255) DEFAULT NULL,
        //         `status` VARCHAR(255) DEFAULT NULL,
        //         `remarks` VARCHAR(255) DEFAULT NULL,
        //         `date_logs` VARCHAR(255) DEFAULT NULL,
        //         `date_created` VARCHAR(255) DEFAULT NULL,
        //         INDEX (`id`)
        //     );";
        //     $connect->exec($create);
        // }

        $SECTIONS_TABLE = 'sections'; //
        $query = "SHOW TABLES LIKE '$SECTIONS_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $SECTIONS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `section` VARCHAR(255) DEFAULT NULL,
                `year_level` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $COMPONENTS_TABLE = 'components'; //
        $query = "SHOW TABLES LIKE '$COMPONENTS_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $COMPONENTS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) DEFAULT NULL,
                `types` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $ROOMS_TABLE = 'rooms'; //
        $query = "SHOW TABLES LIKE '$ROOMS_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $ROOMS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `lab_id` VARCHAR(255) DEFAULT NULL,
                `room` VARCHAR(255) DEFAULT NULL,
                `seats` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $SCHEDULED_TABLE = 'scheduled'; //
        $query = "SHOW TABLES LIKE '$SCHEDULED_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $SCHEDULED_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `lab_id` VARCHAR(255) DEFAULT NULL,
                `room_id` VARCHAR(255) DEFAULT NULL,
                `room_name` VARCHAR(255) DEFAULT NULL,
                `teacher_id` VARCHAR(255) DEFAULT NULL,
                `teacher_name` VARCHAR(255) DEFAULT NULL,
                `section_id` VARCHAR(255) DEFAULT NULL,
                `section_name` VARCHAR(255) DEFAULT NULL,
                `year_level` VARCHAR(255) DEFAULT NULL,
                `subject` VARCHAR(255) DEFAULT NULL,
                `days` VARCHAR(255) DEFAULT NULL,
                `times_in` VARCHAR(255) DEFAULT NULL,
                `times_out` VARCHAR(255) DEFAULT NULL,
                `military_in` VARCHAR(255) DEFAULT NULL,
                `military_out` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $ROOM_COMPONENT_TABLE = 'room_component'; //
        $query = "SHOW TABLES LIKE '$ROOM_COMPONENT_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $ROOM_COMPONENT_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `room_id` VARCHAR(255) DEFAULT NULL,
                `room_name` VARCHAR(255) DEFAULT NULL,
                `pc_no` VARCHAR(255) DEFAULT NULL,
                `component_id` VARCHAR(255) DEFAULT NULL,
                `component_name` VARCHAR(255) DEFAULT NULL,
                `component_type` VARCHAR(255) DEFAULT NULL,
                `component_status` VARCHAR(255) DEFAULT NULL,
                `component_remarks` VARCHAR(255) DEFAULT NULL,
                `requested_by` VARCHAR(255) DEFAULT NULL,
                `requested_name` VARCHAR(255) DEFAULT NULL,
                `requested_date` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $SCHEDULED_STUDENTS_TABLE = 'scheduled_student'; //
        $query = "SHOW TABLES LIKE '$SCHEDULED_STUDENTS_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $SCHEDULED_STUDENTS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `scheduled_id` VARCHAR(255) DEFAULT NULL,
                `pc_no` VARCHAR(255) DEFAULT NULL,
                `student_id` VARCHAR(255) DEFAULT NULL,
                `student_name` VARCHAR(255) DEFAULT NULL,
                `lab_id` VARCHAR(255) DEFAULT NULL,
                `room_id` VARCHAR(255) DEFAULT NULL,
                `room_name` VARCHAR(255) DEFAULT NULL,
                `teacher_id` VARCHAR(255) DEFAULT NULL,
                `teacher_name` VARCHAR(255) DEFAULT NULL,
                `section_id` VARCHAR(255) DEFAULT NULL,
                `section_name` VARCHAR(255) DEFAULT NULL,
                `year_level` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $CATEGORY_TABLE = 'reports_category'; //
        $query = "SHOW TABLES LIKE '$CATEGORY_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $CATEGORY_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `category` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $REPORTS_TABLE = 'reports_file'; //
        $query = "SHOW TABLES LIKE '$REPORTS_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $REPORTS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `type` VARCHAR(255) DEFAULT NULL,
                `image` VARCHAR(255) DEFAULT NULL,
                `category` VARCHAR(255) DEFAULT NULL,
                `pc_status` VARCHAR(255) DEFAULT NULL,
                `issue` TEXT DEFAULT NULL,
                `scheduled_id` VARCHAR(255) DEFAULT NULL,
                `pc_no` VARCHAR(255) DEFAULT NULL,
                `student_id` VARCHAR(255) DEFAULT NULL,
                `student_name` VARCHAR(255) DEFAULT NULL,
                `lab_id` VARCHAR(255) DEFAULT NULL,
                `room_id` VARCHAR(255) DEFAULT NULL,
                `room_name` VARCHAR(255) DEFAULT NULL,
                `teacher_id` VARCHAR(255) DEFAULT NULL,
                `teacher_name` VARCHAR(255) DEFAULT NULL,
                `section_id` VARCHAR(255) DEFAULT NULL,
                `section_name` VARCHAR(255) DEFAULT NULL,
                `year_level` VARCHAR(255) DEFAULT NULL,
                `action_taken` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_verified` VARCHAR(255) DEFAULT NULL,
                `date_solved` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

    } catch(PDOException $err){   
        $connect = null;
        return;
    }

?>