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
			document.cookie = "dark" + "=" + "true";
	}
	else
		document.cookie = "dark" + "=" + "true";
};

function mode() {
	if (typeof mode.dark == 'undefined')
	{
		if (cookies() == "false")
		{
			document.cookie = "dark" + "=" + "true";
			mode.dark = true;
		}
		else
		{
			document.cookie = "dark" + "=" + "false";
			mode.dark = false;
		}
	}
	else if (mode.dark === true)
	{
		mode.dark = false;
		document.cookie = "dark" + "=" + "false";
	}
	else
	{
		mode.dark = true;
		document.cookie = "dark" + "=" + "true";
	}
	changemode(mode.dark);
}

function changemode($dark) {
	if ($dark === false)
	{
		document.body.style.backgroundColor = "whitesmoke";
		document.getElementById("topbar").style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.79 + ")";
		document.getElementById("footer").style.backgroundColor = "rgba(" + 255 + "," + 255 + "," + 255 + "," + 0.79 + ")";
		document.getElementById("mode").style.backgroundColor = "rgb(" + 30 + "," + 30 + "," + 30 + ")";
		var elementExists = document.getElementById("account");
		if (elementExists != null)
			document.getElementById("account").style.color = "black";
		var elementExists = document.getElementById("output");
		if (elementExists != null)
			document.getElementById("output").style.backgroundColor = "white";
		var elementExists = document.getElementById("photostext");
		if (elementExists != null)
		{
			document.getElementById("photostext").style.color = "black";
			document.getElementById("pen").setAttribute('src', "content/bpencil.png");
		}
	}
	else
	{
		document.body.style.backgroundColor = "rgb(" + 14 + "," + 14 + "," + 14 + ")";
		document.getElementById("topbar").style.backgroundColor = "rgba(" + 30 + "," + 30 + "," + 30 + "," + 0.79 + ")";
		document.getElementById("footer").style.backgroundColor = "rgba(" + 30 + "," + 30 + "," + 30 + "," + 0.79 + ")";
		document.getElementById("mode").style.backgroundColor = "whitesmoke";
		var elementExists = document.getElementById("account");
		if (elementExists != null)
			document.getElementById("account").style.color = "whitesmoke";
		var elementExists = document.getElementById("output");
		if (elementExists != null)
			document.getElementById("output").style.backgroundColor = "black";
		var elementExists = document.getElementById("photostext");
		if (elementExists != null)
		{
			document.getElementById("photostext").style.color = "whitesmoke";
			document.getElementById("pen").setAttribute('src', "content/pencil.png");
		}
	}
}