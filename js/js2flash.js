if(redirectURL == undefined) var redirectURL = '/';
function doSave(testRedirectURL, btn)
{
	if(testRedirectURL) redirectURL = testRedirectURL;
	
	var flash = getFlash();
	if(flash)
	{
		btn.value = 'Saving...';
		btn.disabled = true;
		flash.doSave();
	}
	else
	{
		alert('Couldn\'t find flash object.');
	}
}
function didSave()
{
	//alert('Did Save.');
	window.location.href = redirectURL;
}
function getFlash()
{
	if(navigator.appName.indexOf("Microsoft" != -1))
	{
		return document.getElementById('plancreator');
	}
	else return document.so.plancreator; //!!??
}