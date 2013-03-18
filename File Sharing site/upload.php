<?php

if (empty($_FILES["file"]) or !isset($_FILES["file"])) { // Check if we really have a file

	$message = "There was no file set to upload!!"; // BAD USER! BAD!

} else {

	include 'UploadManager.php';

	$manager = new UploadManager($_FILES["file"]); // Create out upload manager

	$message = $manager->attemptStore(); // Attempt to store the file

}
?>


<!DOCTYPE HTML>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Upload Page</title>
		<meta content="text/html">
		<meta name="description" content="The upload manager of this service!">
		<meta name="author" content="Joshua D. Katz">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="./bootstrap/css/bootstrap.css" rel="stylesheet">

		<style type="text/css">
    		body {
				padding-top: 40px;
				padding-bottom: 40px;
				background-color: #f5f5f5;
			}

			.main {
				max-width: 300px;
				padding: 19px 29px 29px;
				margin: 0 auto 20px;
				background-color: #fff;
				border: 1px solid #e5e5e5;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
				border-radius: 5px;
				-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
				-moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
				box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
			}

			.main .heading {
				margin-bottom: 10px;
			}

    	</style>

	</head>

	<body class="body">

		<div class="container main">

			<h2 class="heading">Thank you for trying!</h2>

			<p>
				<?php echo $message; ?>
			</p>

		</div>

		<script src="./bootstrap/js/bootstrap.js"></script>
		<script src="http://code.jquery.com/jquery.js"></script>
	</body>

</html>
