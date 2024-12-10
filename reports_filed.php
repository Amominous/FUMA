<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

if (!isset($_GET["status"]))
{
	$query .= "SELECT * FROM $REPORTS_TABLE WHERE 
	room_id = '".$_GET["room_id"]."' AND 
	pc_no = '".$_GET["pc_no"]."' AND ";
	if ($_SESSION["user_type"] !== 'Superadmin')
	{
		// $query .= " scheduled_student_id = (SELECT id FROM $SCHEDULED_STUDENTS_TABLE WHERE scheduled_id = '".$_GET["scheduled_id"]."' ) AND 
		// 	teacher_id = '".$_SESSION["user_id"]."' AND ";
		$query .= " teacher_id = '".$_SESSION["user_id"]."' AND ";
	}
}
else
{
	$query .= "SELECT * FROM $REPORTS_TABLE WHERE status = '".$_GET["status"]."' AND ";
	if ($_SESSION["user_type"] !== 'Superadmin')
	{
		$query .= " teacher_id = '".$_SESSION["user_id"]."' AND ";
	}
}
if (isset($_GET["first"]))
{
	$query .= " SUBSTR(date_solved, 1, 10) BETWEEN '".$_GET["first"]."' AND '".$_GET["second"]."' AND ";
}

if(isset($_POST["search"]["value"]))
{
	$query .= '(id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR student_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR year_level LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR section_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR category LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR pc_status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR issue LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR action_taken LIKE "%'.$_POST["search"]["value"].'%" ';
	
	// $query .= 'OR lab_id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR room_name LIKE "%'.$_POST["search"]["value"].'%" ';
	if ($_SESSION["user_type"] == 'Superadmin')
	{
		$query .= 'OR teacher_name LIKE "%'.$_POST["search"]["value"].'%" ';
	}
	if (isset($_GET["status"]))
	{
		$query .= 'OR pc_no LIKE "%'.$_POST["search"]["value"].'%" ';
	}

	$query .= 'OR status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_verified LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_solved LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id DESC ';
}

if($_POST['length'] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

$filtered_rows = $statement->rowCount();

foreach($result as $row)
{
	$sub_array = array();
	
	if (!isset($_GET["status"]))
	{
		$sub_array[] = "<b>By:</b> ".$row['student_name']
			."<br><b>Section:</b> ".$row['year_level']." - ".$row['section_name']
			."<br><b>Room:</b> ".$row['room_name']
			."<br><b>Date:</b> ".$row['date_created'];
	
		$details = "";
		if ($_SESSION["user_type"] == 'Superadmin')
		{
			if ($row['status'] !== 'Pending')
			{
				$details .= "<br><b>By:</b> ".$row['teacher_name'];
			}
		}
		$sub_array[] = "<b>Category:</b> ".$row['category']
			."<br><b>PC Status:</b> ".$row['pc_status']
			."<br><b>Issue:</b> ".$row['issue']
			.$details;
		
		$status = '';
		if ($row['status'] == 'Pending')
		{
			$status = '<span class="badge badge-secondary p-2">'.$row['status'].'</span>';
		}
		else if ($row['status'] == 'Ongoing')
		{
			$status = '<span class="badge badge-primary p-2">'.$row['status'].'</span>'."<br><b>Verified:</b> ".$row['date_verified'];
		}
		else if ($row['status'] == 'Not Verified')
		{
			$status = '<span class="badge badge-danger p-2">'.$row['status'].'</span>'."<br><b>Remarks:</b> ".$row['action_taken'];
		}
		else
		{
			$status = '<span class="badge badge-success p-2">'.$row['status'].'</span>'
				."<br><b>Verified:</b> ".$row['date_verified']
				."<br><b>Remarks:</b> ".$row['action_taken']
				."<br><b>Solved:</b> ".$row['date_solved'];
		}
		$sub_array[] = $status;
	}
	else
	{
		$image_data = !empty($row['image']) ? $row['image'] : null;  // Assuming 'image' is already Base64-encoded in the database

		if ($image_data) {
			// Since the image is base64-encoded, we can extract the MIME type from the beginning of the string
			if (preg_match('/^data:(image\/(?:jpeg|png|jpg));base64,/', $image_data, $matches)) {
				// MIME type matched
				$mime_type = $matches[1];  // Extracted MIME type from base64 string

				// Dynamically set the base64 image with the correct MIME type
				$image_url = $image_data;  // The image URL is directly the base64 string
				
				// Create an anchor tag with image preview
				$sub_array[] = '<a data-magnify="gallery" href="' . $image_url . '">
									<img class="img-fluid" style="height: 50px; width: 50px;" src="' . $image_url . '" alt="Image">
								</a>';
			} else {
				// If the image MIME type is not one of the allowed ones, display nothing
				$sub_array[] = '';  
			}
		} else {
			// If no image data is found, display nothing
			$sub_array[] = '';  
		}

		$sub_array[] = $row['room_name'];
		$sub_array[] = $row['year_level']." - ".$row['section_name'];
		$sub_array[] = $row['student_name'];
		$sub_array[] = $row['date_created'];
		$sub_array[] = $row['pc_no'];
		$sub_array[] = $row['category'];
		$sub_array[] = $row['pc_status'];
		$sub_array[] = $row['issue'];

		if ($row['status'] == 'Pending')
		{
			if ($_SESSION["user_type"] !== 'Superadmin')
			{
				$sub_array[] = '
					<div class="btn-group btn-group-md ">
						<a href="#" class="btn btn-success status " name="status" id="'.$row["id"].'" data-toggle="tooltip" 
						data-placement="top" title="Verify"><i class="fa fa-history"></i> Verify</a>
						<a href="#" class="btn btn-danger status " name="status" id="'.$row["id"].'" data-toggle="tooltip" 
						data-placement="top" title="Not Verify"><i class="fa fa-times-circle"></i> Not Verify</a>
					</div>
				';
			}
		}
		else if ($row['status'] == 'Ongoing')
		{
			$sub_array[] = $row['date_verified'];
			if ($_SESSION["user_type"] == 'Superadmin')
			{
				$sub_array[] = $row['teacher_name'];
				$sub_array[] = '
					<div class="btn-group btn-group-md ">
						<a href="#" class="btn btn-success status " name="status" id="'.$row["id"].'" data-toggle="tooltip" 
						data-placement="top" title="Solve"><i class="fa fa-check-circle"></i> Solve</a>
					</div>
				';
			}
		}
		else if ($row['status'] == 'Not Verified')
		{
			$sub_array[] = $row['action_taken'];
			$sub_array[] = $row['date_verified'];
		}
		else
		{
			$sub_array[] = $row['date_verified'];
			if ($_SESSION["user_type"] == 'Superadmin')
			{
				$sub_array[] = $row['teacher_name'];
			}
			$sub_array[] = $row['action_taken'];
			$sub_array[] = $row['date_solved'];
		}
		if ($_SESSION["user_type"] == 'Superadmin')
		{
			$sub_array[] = '
				<div class="btn-group btn-group-md ">
					<a href="#" class="btn btn-success status " name="status" id="'.$row["id"].'" data-toggle="tooltip" 
					data-placement="top" title="Archive"><i class="fa fa-archive"></i> Archive</a>
				</div>
			';
		}
	}

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $REPORTS_TABLE, 
		!isset($_GET["status"]) ? $_GET["room_id"] : '', 
		!isset($_GET["status"]) ? $_GET["pc_no"] : '', 
		$SCHEDULED_STUDENTS_TABLE, 
		($_SESSION["user_type"] !== 'Superadmin' ? (!isset($_GET["status"]) ? $_GET["scheduled_id"] : '') : ''),
		isset($_GET["status"]) ? $_GET["status"] : ''
	),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE, $room_id, $pc_no, $SCHEDULED_STUDENTS_TABLE, $scheduled_id, $status)
{
	if (!isset($_GET["status"]))
	{
		$query = "";
		if ($_SESSION["user_type"] !== 'Superadmin')
		{
			// $query = " AND scheduled_student_id = (SELECT id FROM $SCHEDULED_STUDENTS_TABLE WHERE scheduled_id = '".$scheduled_id."' )
			// 	AND teacher_id = '".$_SESSION["user_id"]."'  ";
			$query = " AND teacher_id = '".$_SESSION["user_id"]."' ";
		}
		$statement = $connect->prepare("SELECT * FROM $TABLE WHERE room_id = '".$room_id."' AND pc_no = '".$pc_no."'  $query ");
	}
	else
	{
		$query = "";
		if ($_SESSION["user_type"] !== 'Superadmin')
		{
			$query = " AND teacher_id = '".$_SESSION["user_id"]."' ";
		}
		$statement = $connect->prepare("SELECT * FROM $TABLE WHERE status = '".$status."' $query ");
	}
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>