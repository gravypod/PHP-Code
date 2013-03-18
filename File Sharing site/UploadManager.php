<?php

	include 'Utils.php';
	include 'DatabaseManager.php';

	/**
	 * Class UploadManager
	 *
	 */
	class UploadManager
	{

		private $maxSize = 512; // Max file size in MB
		private $file; // File
		private $name; // Name
		private $database; // Database object
		private $hashedName; // Hashed name of the file
		private $storingLocation; // Storing location of the file
		private $downloadLink; // Download location
		private $tempName; // Temp file name
		private $isInTemp;

		/**
		 * Manage file uploads to the server
		 *
		 * @param $downloading - File that we want to upload (from $_FILES['name'])
		 */
		public function UploadManager($downloading)
		{
			$this->file = $downloading;

			$this->name = $downloading['name'];

			$this->tempName = $downloading['tmp_name'];

			$this->isInTemp = is_uploaded_file($this->tempName);

			if ($this->isInTemp) {
				$this->database = new DatabaseManager();

				$this->hashedName = Utils::getStoredName($this->tempName);

				$this->storingLocation = Utils::getStorageLocation($this->tempName);

				$this->downloadLink = Utils::downloadLink($this->hashedName);
			}
		}

		/**
		 * Attempt to store the file on the server.
		 *
		 * @return string - An error message if the file was unable to be saved or the download link.
		 */
		public function attemptStore()
		{
			if (!$this->isInTemp) {
				return "something went wrong! " . printf($_FILES);
			}
			$outcome = $this->isValid();

			switch ($outcome) {
				case "SIZE":
					$output = "That file is too large!";
					break;
				case "EXISTS":
					$output = "That file already exists, download it " . $this->makeHref('here.');
					break;
				case "OK":
					$this->storeFile();
					$output = "Upload complete. Download it " . $this->makeHref('here.');
					break;
				default:
					$output = "Something strange happened!";

					return $output;
			}

			$this->database->close();

			return $output;

		}

		/**
		 * Check to see this file can be uploaded.
		 *
		 * @return string - "EXISTS" if the file exists, "SIZE" if the file is too large, "OK" if the file is ok to be uploaded
		 */
		public function isValid()
		{

			// Check to see if we already have this file.
			if (file_exists($this->storingLocation)) {
				return "EXISTS";
			}

			// Convert size to MB and check to see if its over the max allowed size.
			if ((($this->file["size"] / 1024) / 1024) > $this->maxSize) {
				return "SIZE";
			}

			return 'OK';

		}

		/**
		 * Store the file on the server and add the hash-to-name lookup in out database
		 */
		public function storeFile()
		{
			$this->database->addFile($this->hashedName, $this->name);
			move_uploaded_file($this->tempName, $this->storingLocation);
		}

		/**
		 * Create an href to the download URL
		 *
		 * @param string $linkingWord = Default is "here"
		 * @param string $options = Default is "target='_blank'"
		 *
		 * @return string - Href
		 */
		public function makeHref($linkingWord = "here", $options = "target='_blank'")
		{
			return "<a href='" . $this->downloadLink . "' " . $options . "/>" . $linkingWord . "</a>";
		}

	}