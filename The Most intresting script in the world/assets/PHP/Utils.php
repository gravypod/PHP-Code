<?php

	class Utils
	{

		private static $defaultWidth = 640;

		public static function sigDirectory()
		{
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

			return $sigDir;

		}

		public static function getQuote($directory)
		{
			if (!isset($_POST["text"])) {
				if (!isset($_GET["text"])) {
					$f_contents = file($directory . "quotes.txt");
					$line = $f_contents[array_rand($f_contents)];
				} else {
					$line = $_GET["text"];
				}
			} else {
				$line = $_POST["text"];
			}

			return $line;
		}

		public static function createImageObject($textWidth, $imageFileName)
		{
			$imageInfo = getimagesize($imageFileName); // load our image
			$imageType = $imageInfo[2];

			switch ($imageType) {
				case IMAGETYPE_GIF:
					$firstImage = imagecreatefromgif($imageFileName);
					break;
				case IMAGETYPE_JPEG:
					$firstImage = imagecreatefromjpeg($imageFileName);
					break;
				case IMAGETYPE_PNG:
					$firstImage = imagecreatefrompng($imageFileName);
					break;
			}

			$firstY = imagesy($firstImage);

			$firstX = imagesx($firstImage);

			$image = imagecreatetruecolor($firstX + $textWidth, $firstY);

			imagecopy($image, $firstImage, imagesx($image) - $firstX, imagesy($image) - $firstY, 0, 0, $firstX, $firstY);

			imagedestroy($firstImage);


			return $image;
		}

		public static function findHead($dir)
		{

			$files = glob($dir . "head.*");

			return $files[0];

		}

		public static function getDefaultWidth()
		{
			return Utils::$defaultWidth;
		}

	}