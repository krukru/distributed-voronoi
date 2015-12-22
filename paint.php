<?php

require('LZString.php');

$imagePath = 'images/test.bmp';
$paletteKey = 'test.palette';
$pendingTasksKey = 'test.pending';

$data = LZString::decompressFromBase64($_POST['data']);
$json = json_decode($data, true);

$taskId = $json['taskId'];
$x = $json['x'];
$y = $json['y'];
$columns = $json['columns'];
$rows = $json['rows'];
$solution = $json['solution'];

$image = new Imagick($imagePath);
$areaIterator = $image->getPixelRegionIterator($x, $y, $columns, $rows);

$index = 0;
$bool = true;
$colorPalette = apc_fetch($paletteKey, $bool);
foreach ($areaIterator as $rowIterator) {
	foreach ($rowIterator as $pixel) {
		$nearestPoint = $solution[$index];
		$index += 1;
		if ($nearestPoint != -1) {
			$color = $colorPalette[$nearestPoint];
			$pixel->setColor(sprintf("rgb(%s, %s, %s)", $color['r'], $color['g'], $color['b']));
		} else {
			$pixel->setColor("rgb(0, 0, 0)"); //pixel is already black
		}
	}
	$areaIterator->syncIterator();
}
$saveOk = $image->writeImage();
$image->setImageFormat('jpg');
$image->writeImage('images/test.jpg');

if ($saveOk) {
	$pendingTasks = apc_fetch($pendingTasksKey);
	foreach ($pendingTasks as $key => $task) {
  		if($task['taskId'] == $taskId) {
  			unset($pendingTasks[$key]);
  			apc_store($pendingTasksKey, $pendingTasks);
			break;
		}
	}
}
