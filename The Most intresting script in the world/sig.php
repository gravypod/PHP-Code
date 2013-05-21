<?php

	header("Content-type: image/png");

	$startX = 5; // where the text will start
	$startY = 30; // where the text will start
	$fontSize = 20; // Size of the font
	$lineJump = 23; // How much space to skip when writing a new line (y axis)
	$imageFileName = "blankSig.jpg";
	$fontFileName = "./font.ttf";
	// Text colour
	$red = 255;
	$green = 255;
	$blue = 255;

	if (!isset($_POST["text"])) {
		if (!isset($_GET["text"])) {
			$f_contents = file("quotes.txt");
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


	$white = imagecolorallocate($image, $red, $green, $blue);

	if (strpos($line, ',') !== false) {

		$quote = explode(", ", $line);
		$quote[0] = $quote[0] . ",";
		foreach ($quote as $q) {
			imagettftext($image, $fontSize, 0, $startX, $startY, $white, $fontFileName, $q);
			$startY += 23;
		}

	} else {
		imagettftext($image, 20, 0, $startX, $startY, $white, $fontFileName, $line);
	}

	imagepng($image);

	imagedestroy($image);


