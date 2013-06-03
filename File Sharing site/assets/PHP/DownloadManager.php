<?php

	require_once 'Utils.php';
	require_once 'DatabaseManager.php';

	class DownloadManager
	{

		private $hashedName; // Hashed name of the file
		private $unHashedName; // Un-hashed name of the file

		/**
		 * @param $name - The name of the file
		 */
		public function DownloadManager($name)
		{
			$this->hashedName = $name;

			$database = new DatabaseManager(); // Open the database

			$this->unHashedName = $database->getFileNameFromHash($this->hashedName); // Get the real file name

		}

		/**
		 * Attempt to send the file to the user who requested it
		 */
		public function sendToUser()
		{

			$storedLocation = Utils::getStoredFromHash($this->hashedName, true);

			if (!file_exists($storedLocation) || strpos($this->hashedName, "/") or strpos($this->hashedName, "\\") or strpos($this->hashedName, ".")) {
				die("File does not exist!");
			} else {

				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="' . $this->unHashedName . '"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($storedLocation));
				readfile($storedLocation);
				die();
			}
		}
	}