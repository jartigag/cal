toggleTheme = document.getElementById('toggleTheme');

toggleTheme.addEventListener('click', function teacherEvent() {
	var stylesheets = document.styleSheets;
	var cssLight = stylesheets[stylesheets.length-1];
	var cssDark = stylesheets[stylesheets.length-2];
	if (cssLight.disabled) {
		cssLight.disabled = false;
		cssDark.disabled = true;
	} else {
		cssDark.disabled = false;
		cssLight.disabled = true;
	}
});