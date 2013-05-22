<?php

	include_once "StringManager.php";

	header("Content-type: image/png");

	if (!isset($_POST["type"])) {
		if (!isset($_GET["type"])) {
			$innerFiles = glob('./types/*');
			$sigDir = $innerFiles[array_rand($innerFiles)] . "/";
		} else {
			$sigDir = "./types/" . $_GET["type"] . "/";
		}
	} else {
		$sigDir = "./types/" . $_POST["type"] . "/";
	}

	if (!file_exists($sigDir) and !is_dir($sigDir)) {
		$innerFiles = glob('./types/*');
		$sigDir = $innerFiles[array_rand($innerFiles)] . "/";
	}

	$startX = 5; // where the text will start
	$startY = 30; // where the text will start
	$fontSize = 20; // Size of the font
	$lineJump = 23; // How much space to skip when writing a new line (y axis)
	$imageFileName = $sigDir . "blankSig.jpg";
	$fontFileName = "./font.ttf";
	// Text colour
	$red = 255;
	$green = 255;
	$blue = 255;

	if (!isset($_POST["text"])) {
		if (!isset($_GET["text"])) {
			$f_contents = file($sigDir . "quotes.txt");
			$line = $f_contents[array_rand($f_contents)];
		} else {
			$line = $_GET["text"];
		}
	} else {
		$line = $_POST["text"];
	}

	$imageInfo = getimagesize($imageFileName); // load our image
	$imageType = $imageInfo[2];

	switch ($imageType) {
		case IMAGETYPE_GIF:
			$image = imagecreatefromgif($imageFileName);
			break;
		case IMAGETYPE_JPEG:
			$image = imagecreatefromjpeg($imageFileName);
			break;
		case IMAGETYPE_PNG:
			$image = imagecreatefrompng($imageFileName);
			break;
	}


	$textColour = imagecolorallocate($image, $red, $green, $blue);

	$stringManager = new StringManager($line);
	$lines = $stringManager->getLines();

	foreach ($lines as $q) {
		imagettftext($image, $fontSize, 0, $startX, $startY, $textColour, $fontFileName, $q);
		$startY += 23;
	}

	imagepng($image);

	imagedestroy($image);


