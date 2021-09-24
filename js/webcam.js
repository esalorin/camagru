var width = 320;
var height = 0;
var streaming = false;  
var video = null;
var canvas = null;
var photobutton = null;
var photo = null;
var photodiv = null;
var data = null;
var snap = false;
var newphoto = null;
var overlaid = null;
var overlay_div = null;
var overlay_container = null;
var	filterselection = false;
var fileToUpload = null;
var uploadbtn = null;
var save = null;
var	filter = 0;
var	uploaded = 0;

function startup() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    newphoto = document.getElementById('new');
    fileToUpload = document.getElementById('fileToUpload');
    uploadbtn = document.getElementById('uploadbutton');
    overlaid = document.getElementById('overlaid');
    overlay_div = document.getElementById('overlay-div');
    overlay_container = document.getElementById('overlay-container');
    save = document.getElementById('save');
    photo = document.getElementById('photo');
    photodiv = document.getElementById('photodiv');
    photobutton = document.getElementById('photobutton');

    // access video stream from webcam
    navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        })
        // on success, stream it in video tag
        .then(function(stream) {
            video.srcObject = stream;
            video.play();
        })
        .catch(function(err) {
            console.log("An error occurred: " + err);
        });

    video.addEventListener('canplay', function(ev) {
        if (!streaming) {
            height = video.videoHeight / (video.videoWidth / width);

            if (isNaN(height)) {
                height = width / (4 / 3);
            }

            video.setAttribute('width', width);
            video.setAttribute('height', height);
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);
            streaming = true;
        }
    }, false);
	
	uploadbtn.addEventListener('click', function(ev) {
        uploadpicture();
        ev.preventDefault();
	}, false);

	newphoto.style.display = "none";
	newphoto.addEventListener('click', function(ev) {
		clearphoto();
		
        ev.preventDefault();
	}, false);
	
	save.style.display = "none";
	save.addEventListener('click', function(ev) {
        savephoto();
        ev.preventDefault();
	}, false);

	photobutton.style.backgroundColor = "gray";
	photobutton.style.cursor = "default";
    photobutton.addEventListener('click', function(ev) {
        takepicture();
        ev.preventDefault();
	}, false);

	activatefilters();
	
    window.addEventListener("resize", function(ev) {
        checkdiv();
        ev.preventDefault();
    }, false);

	dragElement(overlay_div);

	clearphoto();
}

function clearphoto() {
    let context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
	context.fillRect(0, 0, canvas.width, canvas.height);
	data = canvas.toDataURL('image/png');
	photo.setAttribute('src', data);
	photodiv.style.zIndex = "15";
	filter = 0;
	photobutton.style.backgroundColor = "gray";
	save.style.display = "none";
	newphoto.style.display = "none";
	uploadbtn.style.display = "inline";
	fileToUpload.style.display = "inline";
	fileToUpload.value = "";
	inactivatefilters();
	removefilter();
	filterselection = true;
	snap = true;
	uploaded = 0;
}

function checkdiv() {
	let div = document.getElementById("add");
	let output = document.getElementById("output");
	if (window.innerWidth <= 725)
	{
		output.style.display = "flex";
		output.style.width = 80 + "%";
		output.style.minWidth = 400 + "px";
		output.style.height = 150 + "px";
		output.style.overflowX = "scroll";
		output.style.overflowY = "hidden";
		div.style.display = "inline";
	}
	else
	{
		output.style.display = "inline";
		output.style.minWidth = "";
		output.style.width = 220 + "px";
		output.style.height = 600 + "px";
		output.style.overflowX = "hidden";
		output.style.overflowY = "scroll";
		div.style.display = "flex";
	}
}

function takepicture() {
	if (snap && width && height && filter != 0) {
		let context = canvas.getContext('2d');
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);

		data = canvas.toDataURL('image/png');
		photo.src = data;
		photodiv.style.display = "inline-block";
		photodiv.style.zIndex = "20";

		newphoto.style.display = "inline";
		save.style.display = "inline";
		inactivatecamera();
		uploadbtn.style.display = "none";
		fileToUpload.style.display = "none";
		filterselection = false;
	}
}

function savephoto()	{
	var ajaxdata = new FormData();
	ajaxdata.append('src', photo.src);
	if (filter != 0)
	{
		ajaxdata.append('filter', overlaid.src);
		ajaxdata.append('top', overlay_div.style.top);
		ajaxdata.append('left', overlay_div.style.left);
	}
	var ajax = new XMLHttpRequest();
	ajax.open('POST', 'savephoto.php');
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200)
		{
			const parent = document.getElementById('output');
			let newImg = document.createElement('img');
			newImg.setAttribute('src', ajax.responseText);
			newImg.setAttribute('width', '200px');
			newImg.setAttribute('height', 'auto');
			parent.insertBefore(newImg, parent.firstChild);
		}
	};
	ajax.send(ajaxdata);
	clearphoto();
}

function getphotos()	{
	var ajax = new XMLHttpRequest();
}

function activatecamera()	{
	if (uploaded == 0)
	{
		photobutton.style.backgroundColor = "rgb(" + 40 + "," + 211 + "," + 194 + ")";
		photobutton.style.cursor = "pointer";
		snap = true;
	}
}

function inactivatecamera()	{
	photobutton.style.backgroundColor = "gray";
	photobutton.style.cursor = "default";
	snap = false;
}

function activatefilters()	{
	for(let i = 1; i <= 7 ;i++)
	{
		document.getElementById(i).addEventListener('click', function(ev){
			let elem = document.getElementById(i);
			if (filterselection && filter != i)
			{
				elem.style.backgroundColor = "rgb(" + 40 + "," + 211 + "," + 194 + ")";
				removefilter();
				if (filter == 0)
					activatecamera();
				else
					document.getElementById(filter).style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.603 + ")";
				filter = i;
				addfilter(i);
			}
			else if (filterselection && filter == i)
			{
				filter = 0;
				removefilter();
				inactivatecamera();
				elem.style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.603 + ")";
			}
			ev.preventDefault();
		}, false);
	}
}

function addfilter(i)	{
	let img = document.getElementById(i).firstChild;
	overlaid.setAttribute('src', img.src);
	if (i == 3 || i == 5 || i == 7)
	{
		overlay_div.style.top = "0px"
		overlay_div.style.left = "0px";
	}
	else if (i == 6)
	{
		overlay_div.style.top = "150px";
		overlay_div.style.left = "150px";
		overlay_div.style.cursor = "move";
	}
	else if (i == 1)
	{
		overlay_div.style.top = "170px";
		overlay_div.style.left = "270px";
		overlay_div.style.cursor = "move";
	}
	else if (i == 4)
	{
		overlay_div.style.top = "50px";
		overlay_div.style.left = "120px";
		overlay_div.style.cursor = "move";
	}
	else
	{
		overlay_div.style.top = "100px";
		overlay_div.style.left = "150px";
		overlay_div.style.cursor = "move";
	}
	overlay_div.style.display = "inline";
}

function removefilter()	{
	overlaid.removeAttribute('src');
	overlay_div.style.display = "none";
}

function inactivatefilters()	{
	for(let i = 1; i <= 7 ;i++)
		document.getElementById(i).style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.603 + ")";
}

function uploadpicture()	{
	let file = document.getElementById('fileToUpload');
	if (file.files && file.files[0])
	{
		var reader = new FileReader();
		reader.onload = function(){
		photo.src = reader.result;
		};
		reader.readAsDataURL(file.files[0]);

		photodiv.style.display = "inline-block";
		photodiv.style.zIndex = "20";

		newphoto.style.display = "inline";
		save.style.display = "inline";
		inactivatecamera();
		uploadbtn.style.display = "none";
		fileToUpload.style.display = "none";
		filterselection = true;
		uploaded = 1;
	}
}

function dragElement(elem)	{
	var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
	elem.onmousedown = dragMouseDown;
	
	function dragMouseDown(e)	{
		e = e || window.event;
		e.preventDefault();
		// get the mouse cursor position at startup:
		pos3 = e.clientX;
		pos4 = e.clientY;
		document.onmouseup = closeDragElement;
		// call a function whenever the cursor moves:
		document.onmousemove = elementDrag;
	}
  
	function elementDrag(e)	{
		e = e || window.event;
		e.preventDefault();
		// calculate the new cursor position:
		pos1 = pos3 - e.clientX;
		pos2 = pos4 - e.clientY;
		pos3 = e.clientX;
		pos4 = e.clientY;
		// set the element's new position:
		if (((overlay_div.offsetTop - pos2) > overlay_container.offsetTop - 20) &&
		((overlay_container.offsetTop + overlay_container.offsetHeight -20) - (overlay_div.offsetTop - pos2 + overlay_div.offsetHeight) > 0))
    		overlay_div.style.top = (overlay_div.offsetTop - pos2) + "px";
		// set the element's new position:
		if (((overlay_div.offsetLeft - pos1) > overlay_container.offsetLeft) &&
		((overlay_container.offsetLeft + overlay_container.offsetWidth) - (overlay_div.offsetLeft - pos1 + overlay_div.offsetWidth) > 0))
			overlay_div.style.left = (overlay_div.offsetLeft - pos1) + "px";
	}
  
	function closeDragElement() {
	  /* stop moving when mouse button is released:*/
	  document.onmouseup = null;
	  document.onmousemove = null;
	}
  }

window.addEventListener('load', startup, false);
window.addEventListener('load', checkdiv, false);