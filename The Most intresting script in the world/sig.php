<?php

	include_once "./assets/PHP/StringManager.php";

	include_once "./assets/PHP/Utils.php";

	$sigDir = Utils::sigDirectory();

	$line = Utils::getQuote($sigDir);

	$startX = 5; // where the text will start

	$startY = 30; // where the text will start

	$fontHeight = 20; // Size of the font

	$fontWidth = 12;

	$spaceBetweenLines = 3;

	$lineJump = $fontHeight + $spaceBetweenLines; // How much space to skip when writing a new line (y axis)

	$headTemplate = Utils::findHead($sigDir); // The image file to render text on

	$fontFileName = "./assets/font/font.ttf"; // The font file

	// Text colour ( http://www.rapidtables.com/web/color/RGB_Color.htm )
	$red = 255;
	$green = 255;
	$blue = 255;

	$stringManager = new StringManager($fontWidth, $fontHeight, $lineJump, $line);

	$image = Utils::createImageObject($stringManager->getWidth(), $headTemplate);

	$textColour = imagecolorallocate($image, $red, $green, $blue);

	$lines = $stringManager->getLines();

	foreach ($lines as $q) {
		imagettftext($image, $fontHeight, 0, $startX, $startY, $textColour, $fontFileName, $q);
		$startY += $lineJump;
	}


	header("Content-type: image/png");

	imagepng($image);

	imagedestroy($image);