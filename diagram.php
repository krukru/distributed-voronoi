<?php

header('Content-type: image/jpeg');

if (file_exists('images/test.jpg') == false) {
	$image = new \Imagick('images/test.bmp');
	$image->setImageFormat('jpg');
	$image->writeImage('images/test.jpg');
}
require ('images/test.jpg');
