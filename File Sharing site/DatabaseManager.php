<?php

	class DatabaseManager
	{

		private $database; // Instance of the database

		private $initDB = "CREATE TABLE IF NOT EXISTS Files(hash TEXT, name TEXT)"; // initiation of the database

		private $addFile = "INSERT INTO Files(Hash, Name) VALUES (:hash, :name)"; // Prepared statements

		private $findName = "SELECT name FROM Files WHERE hash=:hash LIMIT 1";

		public function DatabaseManager()
		{

			$this->database = new SQLite3('files.db');

			if (!$this->database) {

				die($this->database->lastErrorMsg());

			}

			if (!$this->initDB()) {

				die("Cannot execute query" . $this->database->lastErrorMsg());

			}

		}

		/**
		 * @return bool - If the db has fully initialized
		 */
		public function initDB()
		{

			return $this->database->exec($this->initDB);

		}

		/**
		 * @param $hash - Stored name of the file
		 * @param $name - Real name of the file
		 */
		public function addFile($hash, $name)
		{

			$addFilePrep = $this->database->prepare($this->addFile);

			$addFilePrep->bindValue(":hash", substr(SQLite3::escapeString($hash), 0, 32), SQLITE3_TEXT);

			$addFilePrep->bindValue(":name", SQLite3::escapeString($name), SQLITE3_TEXT);

			$addFilePrep->execute();

		}

		/**
		 * @param $hash - The hashed name of the file
		 *
		 * @return mixed - Get the real name
		 */
		public function getFileNameFromHash($hash)
		{

			$getFilePrep = $this->database->prepare($this->findName);

			$getFilePrep->bindValue(":hash", substr(SQLite3::escapeString($hash), 0, 32), SQLITE3_TEXT);

			$row = $getFilePrep->execute()->fetchArray(SQLITE3_ASSOC);

			return $row["name"];

		}

		/**
		 * Close off our handle
		 */
		public function close()
		{

			$this->database->close();

		}
	}