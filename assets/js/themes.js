optnToggleTheme = document.getElementById('optnToggleTheme');
console.log(optnToggleTheme);

optnToggleTheme.addEventListener('click', toggleTheme);

function toggleTheme(event) {
  var stylesheets = document.styleSheets;
  console.log(event);

  for(i=0; i<stylesheets.length; i++) {
  	if (stylesheets[i].href.indexOf(event) !== -1) {
  		stylesheets[i].disabled = false;	
  	} else {
  		stylesheets[i].disabled = true;
  	}
    //stylesheet[i].disabled = (stylesheet[i].href.indexOf(themeName) !== -1) ? false : true;
  }
}