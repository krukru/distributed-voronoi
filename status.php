<?php

$image = new Imagick('images/test.bmp');
$image = $image->flattenImages();

$image->setImageFormat('jpg');


header('Content-type: image/jpeg');
echo $image;
