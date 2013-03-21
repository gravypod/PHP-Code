<?php

	if (!isSet($_GET['download']) or empty($_GET['download'])) { // Check if we have a file to download

		echo "No file specified";

	} else {

		require_once ('DownloadManager.php');

		$fileName = $_GET['download']; // Get the file name

		$manager = new DownloadManager($fileName); // Create a download manager

		// TODO: Maybe add accounts and have paid-file access

		$manager->sendToUser(); // send to out user
	}