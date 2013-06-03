<?php


	/**
	 * Class Utils
	 */
	class Utils
	{

		/**
		 * @var bool - True if we are in debugging mode.
		 */
		public static $debugging = false;

		/**
		 * Max size of a file
		 * @var int
		 */
		public static $maxSize = 104857600;

		/**
		 * Tag to check for downloading a file
		 * EX: $_POST[Utils::$downloadTag);
		 * @var string
		 */
		public static $downloadTag = "download";

		/**
		 * Tag to check for uploading a file
		 * EX: $_POST[Utils::$uploadTag);
		 * @var string
		 */
		public static $uploadTag = "upload";

		/**
		 * Protocol to use
		 * @var string
		 */
		private static $protocol = "http://";

		/**
		 * Location to store all the files
		 * @var string
		 */
		private static $storageLocation = "../../uploads/";

		/**
		 * The size of a hash we get from a file
		 * @var int
		 */
		private static $expectedHashSize = 32;

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

			return $storageName;

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

		/**
		 * Attempt to store a file from a user
		 * @return string - the message we want to send back for our JavaScript to interpret
		 */
		public static function attemptFileUpload()
		{

			require_once "UploadManager.php";

			foreach ($_FILES['file']['name'] as $key => $name) { // Find all the files we are uploading

				// Create our own file information array
				$FILE['name'] = $_FILES['file']['name'][$key];

				$FILE['tmp_name'] = $_FILES['file']['tmp_name'][$key];

				$FILE['size'] = $_FILES['file']['size'][$key];

				if (empty($FILE["name"]) or empty($FILE["size"]) or empty($FILE["tmp_name"])) {
					continue;
				} else {

					$manager = new UploadManager($FILE); // Create out upload manager

					// Hacky fix so PHP's encode function works (start)

					$fs['message'] = $manager->processFile();

					$fs['name'] = $manager->fileName();

					$fs['url'] = $manager->getDownloadLink();


					$fileList[] = $fs;
					// (end)
				}

			}

			unset($FILE);


			// Return info to our JS client
			if (!isset($fileList)) {
				return json_encode(array("message" => "No files where set!", "name" => "", "url" => ""));
			}

			return json_encode($fileList);

		}

		/**
		 * Attempt to send a file to a user
		 *
		 * @param $file - The file hash we want to use
		 */
		public static function attemptFileDownload($file)
		{

			if (!empty($file)) {
				require_once('DownloadManager.php');

				$fileName = $_GET['download']; // Get the file name

				$manager = new DownloadManager($fileName); // Create a download manager

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

		/**
		 * Get the URL to the downloader script
		 * @return string
		 */
		public static function getServerUrl()
		{
			return Utils::$protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		/**
		 * Output length of the current hash method
		 * @return int
		 */
		public static function getHashSize()
		{
			return Utils::$expectedHashSize;
		}

	}