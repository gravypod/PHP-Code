<?php

	/**
	 * Class Utils
	 */
	class Utils
	{

		private static $protocall = "http://";
		private static $storageLocation = "uploads/"; // Uploads directory
		private static $installDir = "downloader/"; // URL of the iste

		/**
		 * @param $fileLocation - Temp file location
		 *
		 * @return string - The path to where the file will be stored
		 */
		public static function getStorageLocation($fileLocation)
		{
			return Utils::getStoredFromHash(Utils::getStoredName($fileLocation));
		}

		/**
		 * @param $name - Name of the file
		 *
		 * @return string - Get the location the file will be stored from its name
		 */
		public static function getStoredFromHash($name)
		{
			return Utils::$storageLocation . $name;
		}

		/**
		 * This method is used because we need to hash the file names.
		 * This is needed because we are bound to get duplicate named files.
		 *
		 * @param $fileLocation - The temp location of the file
		 *
		 * @return string - The name of the file
		 */
		public static function getStoredName($fileLocation)
		{

			$storageName = md5_file($fileLocation);

			return ($storageName);

		}

		/**
		 * @param $name - Name of the file
		 *
		 * @return string - The download link for the file
		 */
		public static function downloadLink($name)
		{
			return Utils::$protocall . $_SERVER['HTTP_HOST'] . "/" . Utils::$installDir . "download.php?download=" . $name;
		}

	}