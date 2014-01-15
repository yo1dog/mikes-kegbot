var lastHttp = null;

function AJAXHttpRequest(post, url, params, updateFunction, updateFunctionParams, setLastHttpVar)
{
	var http = null;
    
	try
	{
		// IE7+, Firefox, Chrome, Opera, Safari
		if (window.XMLHttpRequest)
			http = new XMLHttpRequest();
		
		// IE5, IE6
		else
			http = new ActiveXObject("Microsoft.XMLHTTP");
	}
	catch (e)
	{
		return null;
	}
    
    if (setLastHttpVar)
    	lastHttp = http;
	
	http.onreadystatechange = function()
	{
    	try
        {
            if (typeof(http.status) == "unknown")
                return;
            
            if (http.status == 404)
                updateFunction(404, updateFunctionParams);
            else if (http.status == 200 && http.readyState == 4)
                updateFunction(http.responseText, updateFunctionParams, http);
        }
        catch(e)
        {
        }
	}
    
	if (post)
	{
		http.open("POST", url, true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.send(params);
	}
	else
	{
		http.open("GET", url + "?" + params, true);
		http.send();
	}
}