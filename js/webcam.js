var width = 320;
var height = 0;
var streaming = false;  
var video = null;
var canvas = null;
var photobutton = null;
var photo = null;
var data = null;
var snap = false;
var newphoto = null;
var overlaid = null;
var	filterselection = false;
var upload = null;
var save = null;
var	filter = 0;

function startup() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    newphoto = document.getElementById('new');
    upload = document.getElementById('upload');
    overlaid = document.getElementById('overlaid');
    save = document.getElementById('save');
    photo = document.getElementById('photo');
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
	
	upload.addEventListener('click', function(ev) {
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


	clearphoto();
}

function clearphoto() {
    let context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
	context.fillRect(0, 0, canvas.width, canvas.height);
	data = canvas.toDataURL('image/png');
	photo.setAttribute('src', data);
	photo.style.zIndex = "15";
	filter = 0;
	photobutton.style.backgroundColor = "gray";
	save.style.display = "none";
	newphoto.style.display = "none";
	upload.style.display = "inline";
	inactivatefilters();
	removefilter();
	filterselection = true;
	snap = true;
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
		div.style.display = "inline";
	}
	else
	{
		output.style.display = "inline";
		output.style.minWidth = "";
		output.style.width = 220 + "px";
		output.style.height = 600 + "px";
		div.style.display = "flex";
	}
}

function takepicture() {
	if (snap && width && height && filter == 1) {
		let context = canvas.getContext('2d');
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);

		data = canvas.toDataURL('image/png');
		photo.src = data;
		photo.style.display = "inline-block";
		photo.style.zIndex = "20";

		newphoto.style.display = "inline";
		save.style.display = "inline";
		inactivatecamera();
		upload.style.display = "none";
		filterselection = false;
	}
}

function savephoto()	{
	const parent = document.getElementById('output');
		let newImg = document.createElement('img');
		newImg.setAttribute('id', 'newimg');
		newImg.setAttribute('src', data);
		newImg.setAttribute('width', "200px");
		newImg.setAttribute('height', "auto");
		parent.insertBefore(newImg, parent.firstChild);
		myAjax();
		clearphoto();
}

function activatecamera()	{
	photobutton.style.backgroundColor = "rgb(" + 40 + "," + 211 + "," + 194 + ")";
	photobutton.style.cursor = "pointer";
	snap = true;
}

function inactivatecamera()	{
	photobutton.style.backgroundColor = "gray";
	photobutton.style.cursor = "default";
	snap = false;
}

function activatefilters()	{
	for(let i = 1; i <= 6 ;i++)
	{
		document.getElementById(i).addEventListener('click', function(ev){
			let elem = document.getElementById(i);
			if (filterselection && elem.style.backgroundColor == "rgb(40, 211, 194)" && filter == 1)
			{
				elem.style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.603 + ")";
				removefilter();
				filter--;
				inactivatecamera();
			}
			else if (filterselection && filter == 0)
			{
				filter++;
				addfilter(i);
				activatecamera();
				elem.style.backgroundColor = "rgb(" + 40 + "," + 211 + "," + 194 + ")";
			}
			ev.preventDefault();
		}, false);
	}
}

function addfilter(i)	{
	let img = document.getElementById(i).firstChild;
	overlaid.setAttribute('src', img.src);
	if (i == 3 || i == 5)
	{
		overlaid.style.width = "400px";
		overlaid.style.height = "300px";
		overlaid.style.top = "0px"
		overlaid.style.left = "0px";
	}
	else if (i == 6)
	{
		overlaid.style.width = "120px";
		overlaid.style.top = "150px"
		overlaid.style.left = "150px";
		overlaid.style.height = "auto";
	}
	else if (i == 1)
	{
		overlaid.style.width = "100px";
		overlaid.style.top = "170px"
		overlaid.style.left = "270px";
		overlaid.style.height = "auto";
	}
	else if (i == 4)
	{
		overlaid.style.width = "150px";
		overlaid.style.top = "50px"
		overlaid.style.left = "120px";
		overlaid.style.height = "auto";
	}
	else
	{
		overlaid.style.width = "100px";
		overlaid.style.top = "100px"
		overlaid.style.left = "150px";
		overlaid.style.height = "auto";
	}
	overlaid.style.display = "inline";
}

function removefilter()	{
	overlaid.removeAttribute('src');
	overlaid.style.display = "none";
}

function inactivatefilters()	{
	for(let i = 1; i <= 6 ;i++)
	{
		document.getElementById(i).style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.603 + ")";
	}
}

function uploadpicture()	{
	photo.src = URL.createObjectURL(event.target.files[0]);
}

function myAjax() {
	var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        ;
      }
    };
    xmlhttp.open("POST", "savephoto.php?test=ok", true);
    xmlhttp.send();
}

window.addEventListener('load', startup, false);
window.addEventListener('load', checkdiv, false);