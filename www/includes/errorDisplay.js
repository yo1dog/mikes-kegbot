var errorWindow = document.createElement("div");
var errorWindowContainer = document.createElement("div");
var errorWindowTitle = document.createElement("span");
var errorWindowMessage = document.createElement("div");
var errorWindowClose = document.createElement("div");

errorWindow.id = "error-window";
errorWindowContainer.id = "error-window-container";
errorWindowTitle.id = "error-window-title";
errorWindowMessage.id = "error-window-message";
errorWindowClose.id = "error-window-close";

errorWindowContainer.appendChild(document.createTextNode("Error on "));
errorWindowContainer.appendChild(errorWindowTitle);
errorWindowContainer.appendChild(errorWindowMessage);

errorWindow.appendChild(errorWindowContainer);

document.body.insertBefore(errorWindow, document.body.firstChild);

function showError(title, errorMessage)
{
	errorWindow.style.display = "block";
	errorWindowTitle.innerHTML = title;
	errorWindowMessage.innerHTML = errorMessage.replace(/\n/g, "<br />");
}

function hideErrorWindow()
{
	errorWindow.style.display = "none";
}
