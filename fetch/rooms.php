<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

if ($_SESSION["user_type"] == 'Superadmin') 
{
	$query .= "SELECT * FROM $ROOMS_TABLE WHERE ";
	
	if(isset($_POST["search"]["value"]))
	{
		$query .= '(id LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR lab_id LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR room LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR seats LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR status LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" )';
	}
}
else
{
	$query .= "SELECT * FROM $SCHEDULED_TABLE WHERE teacher_id = '".$_SESSION['user_id']."' AND ";
	
	if(isset($_POST["search"]["value"]))
	{
		$query .= '(id LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR room_name LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR teacher_name LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR section_name LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR year_level LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR subject LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR days LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR times_in LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR times_out LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR military_in LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR military_out LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" )';
	}
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
	
	if ($_SESSION["user_type"] == 'Superadmin') 
	{
		// $sub_array[] = $row['id'];
		$sub_array[] = $row['lab_id'];
		$sub_array[] = $row['room'];
		$sub_array[] = $row['seats'];
		$sub_array[] = $row['date_created'];
		
		if($row['status'] == 'Active')
		{
			$sub_array[] = '
			<div class="btn-group btn-group-md ">
				<a href="#" class="btn btn-success status " data-status="Inactive" name="status" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-check-circle"></i> Active</a>
			</div>';
		}
		else
		{
			$sub_array[] = '
			<div class="btn-group btn-group-md "> 
				<a href="#" class="btn btn-danger status " data-status="Active" name="status" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-times-circle"></i> Inactive</a>
			</div>';
		}
	
		// $sub_array[] = '
		// 	<div class="btn-group btn-group-md ">
		// 		<a href="#" class="btn btn-success update " name="update" id="'.$row["id"].'" data-toggle="tooltip" 
		// 		data-placement="top" title="Update"><i class="fa fa-user-edit"></i> Update</a>
		// 	</div>
		// ';
	
		$sub_array[] = '
			<div class="btn-group btn-group-md ">
				<a href="#" class="btn btn-success update " name="update" id="'.$row["id"].'" data-toggle="tooltip" 
				data-placement="top" title="Update"><i class="fa fa-user-edit"></i> Update</a>
				<a href="#" class="btn btn-success schedule " name="schedule" id="'.$row["id"].'" data-room="'.$row["room"].'" 
				data-toggle="tooltip" data-placement="top" title="Schedule"><i class="fa fa-calendar-alt"></i> Schedule</a>
				<a href="#" class="btn btn-success layout " name="layout" id="'.$row["id"].'" data-room="'.$row["room"].'" 
				data-toggle="tooltip" data-placement="top" title="Details"><i class="fa fa-info-circle"></i> Details</a>
			</div>
		';
	
		// $sub_array[] = '
		// 	<div class="btn-group btn-group-md ">
		// 		<a href="#" class="btn btn-success layout " name="layout" id="'.$row["id"].'" data-room="'.$row["room"].'" 
		// 		data-toggle="tooltip" data-placement="top" title="Layout"><i class="fa fa-map"></i> Layout</a>
		// 	</div>
		// ';
	}
	else
	{
		$sub_array[] = $row['lab_id'];
		$sub_array[] = $row['room_name'];
		$sub_array[] = $row['year_level']." - ".$row['section_name']; 
		$sub_array[] = $row['subject'];
		$sub_array[] = $row['days'];
		$sub_array[] = $row['times_in']." - ".$row['times_out'];
	
		$sub_array[] = '
			<div class="btn-group btn-group-md ">
				<a href="#" class="btn btn-success layout " name="layout" id="'.$row["room_id"].'" data-room="'.$row["room_name"].'" data-scheduled_id="'.$row["id"].'" 
				data-toggle="tooltip" data-placement="top" title="Details"><i class="fa fa-info-circle"></i> Details</a>
			</div>
		';
	}

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, ($_SESSION["user_type"] == 'Superadmin' ? $ROOMS_TABLE : $SCHEDULED_TABLE), $_SESSION['user_id']),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE, $user_id)
{
	if ($_SESSION["user_type"] == 'Superadmin') 
	{
		$statement = $connect->prepare("SELECT * FROM $TABLE  ");
	}
	else
	{
		$statement = $connect->prepare("SELECT * FROM $TABLE WHERE teacher_id = '".$user_id."' ");
	}
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>