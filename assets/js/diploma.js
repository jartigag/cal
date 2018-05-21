var verDiploma = document.getElementsByClassName('verDiploma');

for (var i = 0; i < verDiploma.length; i++) verDiploma[i].addEventListener('click', showDiploma(i));

function showDiploma(i) {
    return function() {

        cerrarDiploma.style.display = "inline";
        var diploma = document.createElement("img");
        diploma.className = 'centered';
        diploma.className = 'diploma';
        diploma.setAttribute('src', 'https://http.cat/100'); //TODO: imagen de diploma
        var texto = document.createElement("p");
        texto.className = 'centered';
        texto.className = 'texto';
        texto.textContent = 'Este es tu diploma';

        centered = document.getElementsByClassName('centered');
        centered[0].insertBefore(centered[0].appendChild(diploma), document.getElementById('tablaDiplomas'));
        centered[0].insertBefore(centered[0].appendChild(texto), diploma);
    }
}

var cerrarDiploma = document.getElementById('cerrarDiploma');
cerrarDiploma.addEventListener('click', closeDiploma());

function closeDiploma() {
    return function() {
        var diploma = document.getElementsByClassName('diploma');
        diploma[0].remove();
        cerrarDiploma.style.display = "none";
        var texto = document.getElementsByClassName('texto');
        texto[0].remove();
    }
}