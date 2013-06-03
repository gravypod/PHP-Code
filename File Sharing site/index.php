<?php

	require_once "./assets/PHP/Utils.php";

	if (isset($_POST["upload"]) or isset($_GET["upload"])) {
		die(Utils::attemptFileUpload());
	}

	if (!isset($_GET[Utils::$downloadTag])) {
		if (isset($_POST[Utils::$downloadTag])) {
			Utils::attemptFileDownload($_GET[Utils::$downloadTag]);
		}
	} else {
		Utils::attemptFileDownload($_GET[Utils::$downloadTag]);
	}

	header('Content-type: text/html; charset=utf-8');

?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta id="infoLink" siteURL="<?php echo Utils::getServerUrl(); ?>" maxSize="<?php echo Utils::$maxSize; ?>">
   	 	<title>File Sharing Service</title>
 		<link href="./assets/CSS/bootstrap.min.css" rel="stylesheet">
    	<link href="./assets/CSS/style.css" rel="stylesheet">
	</head>
	<body class="body text-center">
		<div class="container">
			<div class="main">
      			<h2 class="heading">Choose a File</h2>
        		<button type="button" class="btn btn-large" onclick="fileChoose()">Choose Files</button>
        		<button class="btn btn-large btn-primary" type="submit" id="submit">Upload</button>
				<input hidden="true" type="file" name="file" multiple="multiple" id="file">
			</div>
    		<div id="uploaded" class="main" hidden="true">
        		<h2>Uploaded Files</h2>

        		<div class="progress progress-striped active">
         		   	<div class="bar" id="upload_bar"></div>
        		</div>
    		</div>
			<noscript>
       		 	<div class="main"><h1>You are missing out on some cool JS stuff man!</h1></div>
    		</noscript>
		</div>
	</body>
	<script src="./assets/JS/uploader.js"></script>
</html>