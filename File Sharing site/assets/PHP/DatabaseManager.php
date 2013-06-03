<?php

	require_once "Utils.php";

	class DatabaseManager
	{

		private $database; // Instance of the database

		/**
		 * Query to setup the database
		 * @var string
		 */
		private $initDB = "CREATE TABLE IF NOT EXISTS Files(hash TEXT, name TEXT)"; // initiation of the database

		/**
		 * Query to add a file into the database
		 * @var string
		 */
		private $addFile = "INSERT INTO Files(Hash, Name) VALUES (:hash, :name)"; // Prepared statements

		/**
		 *
		 * Query to find a file name from a hash
		 *
		 * @var string
		 */
		private $findName = "SELECT name FROM Files WHERE hash=:hash LIMIT 1";

		/**
		 * Class to manage all the database negotiation
		 */
		public function DatabaseManager()
		{

			$this->database = new PDO("sqlite:./database/files.db");

			if (Utils::$debugging) {
				$this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}

			$this->initDB();

		}

		/**
		 * Init the database and prepare it if it didn't exist
		 */
		private function initDB()
		{

			$this->database->exec($this->initDB);

		}

		/**
		 * @param $hash - Stored name of the file
		 * @param $name - Real name of the file
		 */
		public function addFile($hash, $name)
		{

			$addFilePrep = $this->database->prepare($this->addFile);

			$addFilePrep->bindValue(":hash", $this->filterHash($hash), PDO::PARAM_STR);

			$addFilePrep->bindValue(":name", $name);

			$addFilePrep->execute();

		}

		/**
		 * @param $hash - Hash to sterilize for DB lookup
		 *
		 * @return string - Filtered hash edited for entry/lookup with PDO
		 */
		public function filterHash($hash)
		{
			return substr($hash, 0, Utils::getHashSize());
		}

		/**
		 * @param $hash - The hashed name of the file
		 *
		 * @return mixed - Get the real name
		 */
		public function getFileNameFromHash($hash)
		{

			$getFilePrep = $this->database->prepare($this->findName);

			$getFilePrep->bindValue(":hash", $this->filterHash($hash), PDO::PARAM_STR);

			$getFilePrep->execute();

			$row = $getFilePrep->fetch(PDO::FETCH_ASSOC);

			return $row["name"];

		}

	}