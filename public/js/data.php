<?php
header('Content-Type: application/json');

$locations = \App\Models\Location::all();

$data = array();

foreach ($locations as $location) {
    $row['name'] = $location->name;
    $row['icon'] = $location->icon;
    $row['asset'] = 64;
	$data[] = $row;
}

echo json_encode($data);
?>