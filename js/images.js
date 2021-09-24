var page = 1;
var total_pages;
var images = document.getElementsByClassName("imgdiv");

function pagination()	{
	for (let i = 0; i < images.length; i++) {
		if (i >= (page - 1) * 6 && i < page * 6)
			images[i].style.display = "block";
		else
			images[i].style.display = "none";
	}
	total_pages = Math.ceil(images.length / 6);
	if (total_pages === 1 || total_pages === 0)
	{
		document.getElementById("prev").style.display = "none";
		document.getElementById("next").style.display = "none";
	}
	if (total_pages > 0)
		document.getElementById("pageno").innerHTML = "page " + page + "/" + total_pages;
}

function deleting(event)	{
	open_popup(event.target.parentElement.previousElementSibling.id);
}

document.getElementById("prev").addEventListener('click', function(ev) {
	previouspage();
	ev.preventDefault();
}, false);

document.getElementById("next").addEventListener('click', function(ev) {
	nextpage();
	ev.preventDefault();
}, false);

function previouspage()	{
	if (total_pages > 1 && page > 1)
	{
		page--;
		for (let i = (page - 1) * 6; i < images.length && i < (page + 1) * 6 ;i++)
		{
			if (i < page * 6)
				images[i].style.display = "block";
			else
				images[i].style.display = "none";
		}
		document.getElementById("pageno").innerHTML = "page " + page + "/" + total_pages;
		document.documentElement.scrollTop = 0;
	}
}

function nextpage()	{
	if (total_pages > 1 && page < total_pages)
	{
		page++;
		for (let i = (page - 2) * 6; i < images.length && i < page * 6 ;i++)
		{
			if (i < (page - 1) * 6)
				images[i].style.display = "none";
			else
				images[i].style.display = "block";
		}
		document.getElementById("pageno").innerHTML = "page " + page + "/" + total_pages;
		document.documentElement.scrollTop = 0;
	}
}

function open_popup(value)	{
	document.getElementById("popup_background").style.display = "block";
	document.body.style.overflow = "hidden";
	if (value == "add")
	{
		document.getElementById("popup_text").innerHTML = "To be able to add photos you have to be logged in!";
		document.getElementById("cancel").style.display = "none";
	}
	else
	{
		document.getElementById("popup_text").innerHTML = "You are deleting a picture. Are you sure?";
		document.getElementById("cancel").style.display = "inline";
		document.getElementById("ok").id = value;
	}
	
}

function close_popup(event)	{
	if (event.target.id != "ok")
	{
		if (event.target.id === "cancel")
			event.target.previousElementSibling.id = "ok";
		else
		{
			var id = event.target.id;
			event.target.id = "ok";
			delete_image(id);
		}
	}	
	document.getElementById("popup_background").style.display = "none";
	document.body.style.overflow = "initial";
}

function delete_image(image_id)	{
	var ajaxdata = new FormData();
	ajaxdata.append('image_id', image_id);
	var ajax = new XMLHttpRequest();
	ajax.open('POST', 'delete_image.php');
	ajax.onload = function() {
		if (ajax.status !== 200)
			alert('Request failed.  Returned status of ' + ajax.status);
		else if (ajax.readyState == 4 && ajax.responseText === "deleted")
		{
			document.getElementById(image_id).parentElement.remove();
			if (document.title === "Camagru" || document.title === "Account")
			{
				images = document.getElementsByClassName("imgdiv");
				pagination();
			}
		}
	};
	ajax.send(ajaxdata);
}

function like(elem, image_id)
{
	var ajaxdata = new FormData();
	ajaxdata.append('image_id', image_id);
	var ajax = new XMLHttpRequest();
	ajax.open('POST', 'like.php');
	ajax.onload = function() {
		if (ajax.status !== 200)
			alert('Request failed.  Returned status of ' + ajax.status);
	};
	ajax.send(ajaxdata);
	var likenb = elem.nextElementSibling.innerHTML;
	if (elem.src.includes("content/like.png"))
	{
		likenb++;
		elem.src = "content/liked.png";
	}
	else
	{
		likenb--;
		elem.src = "content/like.png";
	}
	elem.nextElementSibling.innerHTML = likenb;
}