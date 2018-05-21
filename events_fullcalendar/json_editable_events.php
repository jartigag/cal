<?php
include '../includes/db_connect.php';

$userId = $_GET['user_id'];

$stmt=$pdo->query("SELECT class_id FROM events WHERE teacher=1 AND user_id=".$userId);
if ($stmt===false) {
	die("error en la query");
}
while($class=$stmt->fetch(PDO::FETCH_ASSOC)) {
	$classes_array[] = array(
		'class_id' => $class['class_id']
	);
}

for ($i=0;$i<=sizeof($classes_array)-1;$i++) {
	$stmt=$pdo->query("SELECT * FROM classes WHERE id=".$classes_array[$i]['class_id']);
	if ($stmt===false) {
		die("error en la query");
	}
	while($class=$stmt->fetch(PDO::FETCH_ASSOC)) {
		$event_array[] = array(
			'id' => $class['id'],
	        'title' => $class['lesson'],
	        'start' => $class['datetime_start'],
	        'end' => $class['datetime_end'],
	        'url' => 'list_students.php?class_id='.$class['id']
		);
	}
}
echo json_encode($event_array);
?>
