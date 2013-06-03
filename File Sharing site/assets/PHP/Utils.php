<?php


	/**
	 * Class Utils
	 */
	class Utils
	{

		public static $maxSize = 104857600;

		/**
		 * @var bool - True if we are in debugging mode.
		 */
		public static $debugging = false;

		public static $downloadTag = "download";

		public static $javascriptUpload = "upload.php";

		private static $protocol = "http://";

		private static $storageLocation = "../../uploads/"; // Uploads directory

		private static $exprectedHashSize = 32;

		/**
		 * @param $fileLocation - Temp file location
		 *
		 * @return string - The path to where the file will be stored
		 */
		public static function getStorageLocation($fileLocation)
		{
			$storedName = Utils::getStoredName($fileLocation);

			return Utils::getStoredFromHash($storedName);
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
		 * @param $name - Name of file
		 * @param bool $downloading - if this file is being downloaded
		 *
		 * @return string - Get the location the file will be stored from its name
		 */
		public static function getStoredFromHash($name, $downloading = false)
		{

			$nameCharacters = str_split($name);

			$location = Utils::$storageLocation;

			foreach ($nameCharacters as $char) {
				$location .= $char . "/";
			}

			$file = $location . $name;

			if (!file_exists($file)) {
				if (!$downloading) {
					mkdir($location, 0777, true);
				}
			}

			return $file;
		}

		public static function attemptFileUpload()
		{

			require_once "UploadManager.php";

			foreach ($_FILES['file']['name'] as $key => $name) { // TODO: Improve mulit-file handling

				$FILE['name'] = $_FILES['file']['name'][$key];

				$FILE['tmp_name'] = $_FILES['file']['tmp_name'][$key];

				$FILE['size'] = $_FILES['file']['size'][$key];

				$manager = new UploadManager($FILE); // Create out upload manager

				// Hacky fix so PHP's encode function works (start)

				$fs['message'] = $manager->processFile();

				$fs['name'] = $manager->fileName();

				$fs['url'] = $manager->getDownloadLink();


				$fileList[] = $fs;
				// (end)


			}

			unset($FILE);

			return json_encode($fileList);

		}

		public static function attemptFileDownload($file) {

			if (!empty($file)) {
				require_once('DownloadManager.php');

				$fileName = $_GET['download']; // Get the file name

				$manager = new DownloadManager($fileName); // Create a download manager

				// TODO: Maybe add accounts and have paid-file access

				$manager->sendToUser(); // send to out user
			}

			unset($file);

		}

		/**
		 * @param $name - Name of the file
		 *
		 * @return string - The download link for the file
		 */
		public static function downloadLink($name)
		{
			return Utils::getServerUrl() . "?" . Utils::$downloadTag . "=" . $name;
		}

		public static function getServerUrl()
		{
			return Utils::$protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		public static function getHashSize() {
			return Utils::$exprectedHashSize;
		}

	}