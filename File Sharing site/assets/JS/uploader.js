function element(name) {
	return document.getElementById(name);
}
var handleUpload = function (event) {
	event.preventDefault();
	event.stopPropagation();
	var fileInput = element('file');
	var infoLink = element('infoLink');
	var maxSize = parseInt(infoLink.getAttribute("maxSize"));
	var data = new FormData();
	data.append("upload", "true");
	var hasUpload = false;
	for (var i = 0; i < fileInput.files.length; ++i) {
		if (fileInput.files[i].size > maxSize) {
			alert("File " + fileInput.files[i].name + " was too large and will not be uploaded.");
			continue;

		}
		hasUpload = true;
		data.append('file[]', fileInput.files[i]);
	}
	if (!hasUpload) {
		alert("No files where selected to be uploaded!");
		return;
	}
	var request = buildRequest(infoLink);
	element('upload_bar').style.display = 'block';
	element('uploaded').style.display = 'block';
	request.send(data);
};
function buildRequest(infoLink) {
	var request = new XMLHttpRequest();
	request.upload.addEventListener('progress', progressHandler);
	request.upload.addEventListener('load', loadHandler);
	request.upload.addEventListener('error', errorHandler);
	request.addEventListener('readystatechange', stateChangeHandler);
	request.open("POST", infoLink.getAttribute("siteURL"));
	request.setRequestHeader('Cache-Control', 'no-cache');
	element('upload_bar').setAttribute('style', "width: 0%");
	return request;
}
var stateChangeHandler = function (event) {
	if (this.readyState == 4) {
		if (this.status == 200) {
			var links = element('uploaded');
			console.log(this.response);
			var uploaded = eval(this.response);
			var div;
			var a;
			try {
				uploaded.forEach(function (value, arrayIndex, array) {
					div = document.createElement('div');
					if (!(value['message'] == null))
						div.appendChild(document.createTextNode(value['message']));
					a = document.createElement('a');
					if (!(value['url'] == null))
						a.setAttribute('href', value['url']);
					if (!(value['name'] == null))
						a.appendChild(document.createTextNode(value['name']));
					div.appendChild(a);
					links.appendChild(div);
				});
			} catch (e) {
				div = document.createElement('div');
				div.appendChild(document.createTextNode("Error uploading files! Please report this! " + e.toString()));
				links.appendChild(div);
			}
		} else {
			alert("Server said the file was not uploaded. " + this.status)
		}
	}
};
function fileChoose() {
	document.getElementById('file').click();
}
var progressHandler = function (event) {
	if (event.lengthComputable) {
		var percent = event.loaded / event.total;
		var progress = element('upload_bar');
		progress.style.display = 'block';
		progress.setAttribute('style', "width: " + Math.round(percent * 100) + '%');
	}
};
var loadHandler = function (event) {
	element('upload_bar').setAttribute("hidden", "false");
};
var errorHandler = function (event) {
	alert("Upload failed");
};
window.addEventListener('load', function (event) {
	element('uploaded').setAttribute("hidden", "true");
	element('submit').addEventListener('click', handleUpload);
});