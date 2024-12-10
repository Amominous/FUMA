<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $CATEGORY_TABLE WHERE ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR category LIKE "%'.$_POST["search"]["value"].'%" ';
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
	
	$sub_array[] = $row['id'];
	$sub_array[] = $row['category'];
	$sub_array[] = $row['date_created'];
	
	if($row['status'] == 'Active')
	{
		$sub_array[] = '
        <div class="btn-group btn-group-md ">
            <a href="#" class="btn btn-success status " data-status="Inactive" name="status" id="'.$row["id"].'" 
			data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-check-circle"></i> Active</a>
        </div>';
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
            <a href="#" class="btn btn-success update " name="update" id="'.$row["id"].'" data-toggle="tooltip" 
			data-placement="top" title="Update"><i class="fa fa-user-edit"></i> Update</a>
        </div>
    ';

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $CATEGORY_TABLE),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE)
{
	$statement = $connect->prepare("SELECT * FROM $TABLE  ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>