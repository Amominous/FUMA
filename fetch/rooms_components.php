<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

if ($_SESSION["user_type"] == 'Superadmin') 
{
	$query .= "SELECT * FROM $ROOM_COMPONENT_TABLE WHERE room_id = '".$_GET["room_id"]."' AND pc_no = '".$_GET["pc_no"]."' AND ";
}
else
{
	$query .= "SELECT * FROM $ROOM_COMPONENT_TABLE WHERE room_id = '".$_GET["room_id"]."' AND pc_no = '".$_GET["pc_no"]."' AND
	 "; // requested_by IN ('".$_SESSION["user_id"]."', NULL) AND
}

if(isset($_POST["search"]["value"]))
{
	$query .= '(id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR pc_no LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR room_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR component_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR component_type LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR component_status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR component_remarks LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR requested_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR requested_date LIKE "%'.$_POST["search"]["value"].'%" ';
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
	
	if ($_SESSION["user_type"] == 'Superadmin') 
	{
		$name = '';
		$date = '';
		$button = '';
		if (!empty($row['requested_by']))
		{
			$name = '<br><span><b>Requested By: </b><br>'.$row['requested_name'].'</span>';
			$date = '<br><span><b>Date Requested: </b><br>'.$row['requested_date'].'</span>';
			if ($row['status'] == 'Pending')
			{
				$button = '
					<a href="#" class="btn btn-success accept_components " name="accept" id="'.$row["id"].'" 
						data-toggle="tooltip" data-placement="top" title="Accept">
						<i class="fa fa-check-circle"></i> Accept
					</a>';
			}
		}

		$sub_array[] = $row['component_type']." - ".$row['component_name'].$name;

		$sub_array[] = '
			<div class="btn-group btn-group-md ">
				'.$button.'
				<a href="#" class="btn btn-danger remove_components " name="remove" id="'.$row["id"].'" 
					data-toggle="tooltip" data-placement="top" title="Remove">
					<i class="fa fa-times-circle"></i> Remove
				</a>
			</div>
		'.$date;
	}
	else
	{
		$sub_array[] = $row['component_type']." - ".$row['component_name'];
		if ( $row['status'] == 'Active')
		{
			if ( $row['requested_by'] == $_SESSION["user_id"])
			{
				$sub_array[] = '<span><b>Date Requested: </b><br>'.$row['requested_date'].'</span>';
			}
			else
			{
				$sub_array[] = '';
			}
		}
		else
		{
			if ( $row['requested_by'] == $_SESSION["user_id"])
			{
				$sub_array[] = '
					<div class="btn-group btn-group-md ">
						<a href="#" class="btn btn-danger remove_components " name="remove" id="'.$row["id"].'" 
							data-toggle="tooltip" data-placement="top" title="Remove">
							<i class="fa fa-times-circle"></i> Remove
						</a>
					</div><br><span><b>Date Requested: </b><br>
				'.$row['requested_date'].'</span>';
			}
			else
			{
				$sub_array[] = '';
			}
		}
	}

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $ROOM_COMPONENT_TABLE, $_GET["room_id"], $_GET["pc_no"], $_SESSION["user_id"]),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE, $room_id, $pc_no, $user_id)
{
	if ($_SESSION["user_type"] == 'Superadmin') 
	{
		$statement = $connect->prepare("SELECT * FROM $TABLE WHERE room_id = '".$room_id."' AND pc_no = '".$pc_no."' ");
	}
	else
	{
		$statement = $connect->prepare("SELECT * FROM $TABLE WHERE room_id = '".$room_id."' AND pc_no = '".$pc_no."' ");// AND requested_by IN ('".$user_id."', NULL)
	}
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>