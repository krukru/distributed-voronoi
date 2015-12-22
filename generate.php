<?php

$width = 3840;
$height = 2160;

$columnsPerTask = 500;
$rowsPerTask = 500;

$numberOfPoints = 50;
$points = array();
$colorPalette = array();
for ($i = 0; $i < $numberOfPoints; $i++) {
		$points[] = array(
			'x' => rand(0, $width - 1),
			'y' => rand(0, $height - 1)
		);
		$colorPalette[] = array(
			'r' => rand(0, 255),
			'g' => rand(0, 255),
			'b' => rand(0, 255)
		);
}
apc_store('test.points', $points, 0);
apc_store('test.palette', $colorPalette, 0);

$taskId = 1;
$pending = array();
$unsolved = array();
for ($i = 0; $i < $height; $i += $rowsPerTask) {
	for ($j = 0; $j < $width; $j += $columnsPerTask) {
		$unsolved[] = array(
			'taskId' => $taskId,
			'x' => $j,
			'y' => $i,
			'columns' => min($columnsPerTask, $width - $j),
			'rows' => min($rowsPerTask, $height - $i)
		);
		$taskId += 1;
	}
}
shuffle($unsolved);
apc_store('test.pending', $pending, 0);
apc_store('test.unsolved', $unsolved, 0);

$filename = 'test.bmp';
$imagesFolderName = 'images';

$image = new Imagick();
$image->newImage($width, $height, new ImagickPixel('black'));
$image->setImageFormat('bmp');
$image->writeImage(sprintf('%s/%s', $imagesFolderName, $filename));

echo 'gotov';
