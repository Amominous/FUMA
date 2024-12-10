<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $SCHEDULED_TABLE WHERE room_id = '".$_GET["room_id"]."' AND ";

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
	
	$sub_array[] = $row['teacher_name'];
	$sub_array[] = $row['year_level']." - ".$row['section_name']; 
	$sub_array[] = $row['subject'];
	$sub_array[] = $row['days'];
	$sub_array[] = $row['times_in']." - ".$row['times_out']; 
	$sub_array[] = $row['date_created'];

    $sub_array[] = '
        <div class="btn-group btn-group-md ">
            <a href="#" class="btn btn-danger remove_scheduled " name="remove" id="'.$row["id"].'" 
				data-toggle="tooltip" data-placement="top" title="Remove">
				<i class="fa fa-times-circle"></i> Remove
			</a>
        </div>
    ';

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $SCHEDULED_TABLE, $_GET["room_id"]),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE, $room_id)
{
	$statement = $connect->prepare("SELECT * FROM $TABLE WHERE room_id = '".$room_id."' ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>