<?php
include 'includes/db_connect.php';

$stmt=$pdo->query("SELECT * FROM classes");
if ($stmt===false) {
	die("error en la query");
}
while($class=$stmt->fetch(PDO::FETCH_ASSOC)) {
	$event_array[] = array(
		'id' => $class['id'],
        'title' => $class['lesson'],
        'start' => $class['datetime_start'],
        'end' => $class['datetime_end']
	);
}
echo json_encode($event_array);
?>
