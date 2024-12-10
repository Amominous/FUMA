<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $SCHEDULED_STUDENTS_TABLE WHERE student_id = '".$_GET["student_id"]."' AND ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR pc_no LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR lab_id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR room_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR teacher_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR section_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR year_level LIKE "%'.$_POST["search"]["value"].'%" ';
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
	
	$sub_array[] = '<span id="'.$row['id'].'" class="scheduled_id">'.$row['pc_no'].'</span>';
	$sub_array[] = $row['pc_no'];
	$sub_array[] = $row['room_name'];
	$sub_array[] = $row['year_level']." - ".$row['section_name'];
	$sub_array[] = $row['teacher_name'];

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $SCHEDULED_STUDENTS_TABLE, $_GET["student_id"]),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE, $student_id)
{
	$statement = $connect->prepare("SELECT * FROM $TABLE WHERE room_id = '".$student_id."' ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>