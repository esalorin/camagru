function isdark() {
	let cookies = document.cookie.split(';');
	if (cookies.length > 0)
	{
		for (i = 0; cookies[i]; i++)
		{
			let $elem = cookies[i].split('=');
			if ($elem[0] == "dark")
			{
				if ($elem[1] == "true")
					return("src='content/pencil.png'");
				else
					return("src='content/bpencil.png'");
			}
		}
	}
};

function cookies() {
	let cookies = document.cookie.split(';');
	if (cookies.length > 0)
	{
		for (i = 0; cookies[i]; i++)
		{
			let $elem = cookies[i].split('=');
			if ($elem[0] == "dark")
			{
				if ($elem[1] == "true")
					changemode(true);
				else
					changemode(false);
				return ($elem[1]);
			}
		}
		if (!cookies[i])
			document.cookie = "dark" + "=" + "true;SameSite=Lax";
	}
	else
		document.cookie = "dark" + "=" + "true;SameSite=Lax";
};

function mode() {
	if (typeof mode.dark == 'undefined')
	{
		if (cookies() == "false")
		{
			document.cookie = "dark" + "=" + "true;SameSite=Lax";
			mode.dark = true;
		}
		else
		{
			document.cookie = "dark" + "=" + "false;SameSite=Lax";
			mode.dark = false;
		}
	}
	else if (mode.dark === true)
	{
		mode.dark = false;
		document.cookie = "dark" + "=" + "false;SameSite=Lax";
	}
	else
	{
		mode.dark = true;
		document.cookie = "dark" + "=" + "true;SameSite=Lax";
	}
	changemode(mode.dark);
}

function changemode($dark) {
	if ($dark === false)
	{
		document.body.style.backgroundColor = "whitesmoke";
		document.getElementById("topbar").style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.79 + ")";
		document.getElementById("footer").style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.79 + ")";
		var elementExists = document.getElementById("account");
		if (elementExists != null)
			elementExists.style.color = "black";
		var elementExists = document.getElementById("output");
		if (elementExists != null)
			elementExists.style.backgroundColor = "white";
		var elementExists = document.getElementById("photostext");
		if (elementExists != null)
		{
			elementExists.style.color = "black";
			document.getElementById("pen").setAttribute('src', "content/bpencil.png");
		}
		var elementExists = document.getElementById("fileToUpload");
		if (elementExists != null)
			elementExists.style.color = "black";
		var elementExists = document.getElementsByClassName("likecount");
		for (var i = 0; elementExists != null && elementExists[i]; i++)
			elementExists[i].style.color = "black";
		var elementExists = document.getElementById("pageno");
		if (elementExists != null)
			elementExists.style.color = "black";
	}
	else
	{
		document.body.style.backgroundColor = "rgb(" + 14 + "," + 14 + "," + 14 + ")";
		document.getElementById("topbar").style.backgroundColor = "rgba(" + 30 + "," + 30 + "," + 30 + "," + 0.79 + ")";
		document.getElementById("footer").style.backgroundColor = "rgba(" + 30 + "," + 30 + "," + 30 + "," + 0.79 + ")";
		var elementExists = document.getElementById("account");
		if (elementExists != null)
			elementExists.style.color = "whitesmoke";
		var elementExists = document.getElementById("output");
		if (elementExists != null)
			elementExists.style.backgroundColor = "black";
		var elementExists = document.getElementById("photostext");
		if (elementExists != null)
		{
			elementExists.style.color = "whitesmoke";
			document.getElementById("pen").setAttribute('src', "content/pencil.png");
		}
		var elementExists = document.getElementById("fileToUpload");
		if (elementExists != null)
			elementExists.style.color = "white";
		var elementExists = document.getElementsByClassName("likecount");
		for (var i = 0; elementExists != null && elementExists[i]; i++)
			elementExists[i].style.color = "white";
		var elementExists = document.getElementById("pageno");
		if (elementExists != null)
			elementExists.style.color = "white";
	}
}