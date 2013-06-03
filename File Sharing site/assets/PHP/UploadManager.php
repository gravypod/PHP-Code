<?php

	require_once 'Utils.php';
	require_once 'DatabaseManager.php';

	/**
	 * Class UploadManager
	 *
	 */
	class UploadManager
	{

		/**
		 * Name of the file the user is uploading
		 * @var string
		 */
		private $name;

		/**
		 * Temp name of the file
		 * @var string
		 */
		private $tempName;

		/**
		 * The size of the file being used.
		 * @var int
		 */
		private $fileSize;

		/**
		 * Hashed name of the file
		 * @var string
		 */
		private $hashedName;

		/**
		 * Storage location of the file
		 * @var string
		 */
		private $storingLocation;

		/**
		 * Download link for the file
		 * @var string
		 */
		private $downloadLink;

		/**
		 * Manage file uploads to the server
		 *
		 * @param $downloading - File that we want to upload (from $_FILES['name'])
		 */
		public function UploadManager($downloading)
		{
			$this->maxSize = Utils::$maxSize;

			$this->name = $downloading['name'];

			$this->tempName = $downloading['tmp_name'];

			$this->fileSize = $downloading["size"];

			$this->hashedName = Utils::getStoredName($this->tempName);

			$this->storingLocation = Utils::getStorageLocation($this->tempName);

			$this->downloadLink = Utils::downloadLink($this->hashedName);

		}

		/**
		 * Attempt to store the file uploaded by the user
		 * @return string
		 */
		public function processFile()
		{

			if (file_exists($this->storingLocation)) {
				return "That file already existed ";
			}

			// Convert size to MB and check to see if its over the max allowed size.
			if ($this->fileSize > $this->maxSize) {
				return "That file is too large! ";
			}

			// Make sure we aren't going to get tricked into wasting DB space!
			if (move_uploaded_file($this->tempName, $this->storingLocation)) {
				$database = new DatabaseManager();
				$database->addFile($this->hashedName, $this->name);
				return "Upload complete ";
			} else {
				return "Upload error " . $this->storingLocation . " ";
			}



		}

		/**
		 * Hashed file name
		 * @return string
		 */
		public function fileHash()
		{
			return $this->hashedName;
		}

		/**
		 * Normal file name
		 * @return mixed
		 */
		public function fileName()
		{
			return $this->name;
		}

		/**
		 * Download link
		 * @return string
		 */
		public function getDownloadLink()
		{
			return $this->downloadLink;
		}

	}