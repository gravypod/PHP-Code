<?php

	require_once 'Utils.php';
	require_once 'DatabaseManager.php';

	/**
	 * Class UploadManager
	 *
	 */
	class UploadManager
	{

		private $maxSize; // Max file size in MB
		private $file; // File
		private $name; // Name
		private $tempName; // Temp file name
		private $hashedName; // Hashed name of the file
		private $storingLocation; // Storing location of the file
		private $downloadLink; // Download location

		/**
		 * Manage file uploads to the server
		 *
		 * @param $downloading - File that we want to upload (from $_FILES['name'])
		 */
		public function UploadManager($downloading)
		{
			$this->maxSize = Utils::$maxSize;

			$this->file = $downloading;

			$this->name = $downloading['name'];

			$this->tempName = $downloading['tmp_name'];

			$this->hashedName = Utils::getStoredName($this->tempName);

			$this->storingLocation = Utils::getStorageLocation($this->tempName);

			$this->downloadLink = Utils::downloadLink($this->hashedName);

		}

		public function processFile()
		{

			if (file_exists($this->storingLocation)) {
				return "That file already existed ";
			}

			// Convert size to MB and check to see if its over the max allowed size.
			if ((($this->file["size"] / 1024) / 1024) > $this->maxSize) {
				return "That file is too large! ";
			}

			$database = new DatabaseManager();

			$database->addFile($this->hashedName, $this->name);

			move_uploaded_file($this->tempName, $this->storingLocation);

			return "Upload complete ";

		}

		public function fileHash()
		{
			return $this->hashedName;
		}

		public function fileName()
		{
			return $this->name;
		}

		public function getDownloadLink() {
			return $this->downloadLink;
		}

	}