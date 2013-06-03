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
		 * Max file size in MB
		 * @var int
		 */
		private $maxSize;

		/**
		 * File the user is uploading
		 * @var
		 */
		private $file;

		/**
		 * Name of the file the user is uploading
		 * @var
		 */
		private $name;

		/**
		 * Temp name of the file
		 * @var
		 */
		private $tempName;

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
			if ((($this->file["size"] / 1024) / 1024) > ($this->maxSize / 1024) / 1024) {
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

		public function getDownloadLink()
		{
			return $this->downloadLink;
		}

	}