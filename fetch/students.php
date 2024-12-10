<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $STUDENT_TABLE WHERE ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(student_no LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR last_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR first_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR middle_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR email_address LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR section LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR year_level LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR status LIKE "%'.$_POST["search"]["value"].'%" ';
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
	
	$sub_array[] = $row['student_no'];
	$sub_array[] = $row['last_name'];
	$sub_array[] = $row['first_name'];
	$sub_array[] = $row['middle_name'];
	$sub_array[] = $row['email_address'];
	$sub_array[] = $row['year_level']." - ".$row['section'];
	$sub_array[] = $row['date_created'];
	
	if($row['status'] == 'Active')
	{
		$sub_array[] = '
        <div class="btn-group btn-group-md ">
            <a href="#" class="btn btn-success status " data-status="Inactive" name="status" id="'.$row["id"].'" 
			data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-check-circle"></i> Active</a>
        </div>';
	}
	else if($row['status'] == 'Not Activated')
	{
		$sub_array[] = ' <span class="badge badge-danger p-2 text-md"><i class="fa fa-exclamation-circle"></i> Not Activated</span>';
	}
	else
	{
		$sub_array[] = '
        <div class="btn-group btn-group-md "> 
            <a href="#" class="btn btn-danger status " data-status="Active" name="status" id="'.$row["id"].'" 
			data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fa fa-times-circle"></i> Inactive</a>
        </div>';
	}

    $sub_array[] = '
        <div class="btn-group btn-group-md ">
            <a href="#" class="btn btn-success update " name="update" id="'.$row["id"].'" 
			data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-user-edit"></i> Update</a>
        </div>
    ';

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $STUDENT_TABLE),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE)
{
	$statement = $connect->prepare("SELECT * FROM $TABLE ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>