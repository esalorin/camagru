var width = 320;
var height = 0;
var streaming = false;  
var video = null;
var canvas = null;
var photobutton = null;
var photo = null;
var data = null;
var newphoto = null;
var upload = null;
var save = null;
var	filters = 0;

function startup() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    newphoto = document.getElementById('new');
    upload = document.getElementById('upload');
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
	photobutton.style.backgroundColor = "gray";
	save.style.display = "none";
	newphoto.style.display = "none";
	upload.style.display = "inline";
	inactivatefilters();
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
    let context = canvas.getContext('2d');
    if (width && height) {
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);

		data = canvas.toDataURL('image/png');
		photo.src = data;
		photo.style.display = "inline-block";

		newphoto.style.display = "inline";
		save.style.display = "inline";
		inactivatecamera();
		upload.style.display = "none";

    } else {
		clearphoto();
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
	
		clearphoto();
}

function activatecamera()	{
	photobutton.style.backgroundColor = "rgb(" + 40 + "," + 211 + "," + 194 + ")";
	photobutton.style.cursor = "pointer";
}

function inactivatecamera()	{
	photobutton.style.backgroundColor = "gray";
	photobutton.style.cursor = "default";
}

function activatefilters()	{
	for(let i = 1; i <= 6 ;i++)
	{
		document.getElementById(i).addEventListener('click', function(ev){
			let elem = document.getElementById(i);
			if (elem.style.backgroundColor == "rgb(40, 211, 194)")
			{
				elem.style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.603 + ")";
				if (--filters == 0)
					inactivatecamera();
			}
			else
			{
				filters++;
				activatecamera();
				elem.style.backgroundColor = "rgb(" + 40 + "," + 211 + "," + 194 + ")";
			}
			ev.preventDefault();
		}, false);
	}
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

window.addEventListener('load', startup, false);
window.addEventListener('load', checkdiv, false);