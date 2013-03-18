<!DOCTYPE HTML>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>File Sharing Service</title>
		<meta content="text/html">
		<meta name="description" content="A simple file sharing website">
		<meta name="author" content="Joshua D. Katz">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="./bootstrap/css/bootstrap.css" rel="stylesheet">

		<style type="text/css">
    		body {
				padding-top: 40px;
				padding-bottom: 40px;
				background-color: #f5f5f5;
			}

			.chooseFile {
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

			.chooseFile .heading {
				margin-bottom: 10px;
			}

    	</style>

	</head>

	<body class="body">

		<div class="container">

			<form action="upload.php" method="post" enctype="multipart/form-data" class="chooseFile">
				<h2 class="heading">Choose a File</h2>
				<input type="file" name="file" id="file"></br>
				<button class="btn btn-large btn-primary" type="submit">Upload</button>
			</form>

		</div>

		<script src="./bootstrap/js/bootstrap.js"></script>
		<script src="http://code.jquery.com/jquery.js"></script>
	</body>

</html>