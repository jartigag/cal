<?php

$record[0]["title"]="Test 1";
$record[1]["title"]="Test 2";
$record[2]["title"]="Test 3";

$record[0]["start"]="2018-04-21";
$record[1]["start"]="2018-04-28";
$record[2]["start"]="2018-05-05";

$record[0]["end"]="2018-04-22";
$record[1]["end"]="2018-04-28";
$record[2]["end"]="2018-05-05";

$record[0]["id"]="1";
$record[1]["id"]="2";
$record[2]["id"]="3";

$record[0]["allDay"]=False;
$record[1]["allDay"]=True;
$record[2]["allDay"]=False;

for ($i=0; $i<3; $i++) {

    $event_array[] = array(
            'title' => $record[$i]['title'],
            'start' => $record[$i]['start'],
            'end' => $record[$i]['end'],
            'allDay' => $record[$i]['allDay']
    );


}

echo json_encode($event_array);

?>