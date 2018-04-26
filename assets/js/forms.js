function checkSignup(form, username, email, password, conf) {
    // Check the username
    re = /^\w+$/; 
    if(!re.test(username.value)) { 
        alert("El nombre de usuario únicamente puede contener letras, números y guiones bajos. Por favor, pruebe de nuevo."); 
        form.inputUsername.focus();
        return false; 
    }
    
    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('La contraseña debe tener 6 caracteres mínimo. Por favor, pruebe de nuevo.');
        form.inputPassword.focus();
        return false;
    }
    
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(password.value)) {
        alert('La contraseña debe tener al menos una mayúscula, una minúscula y un número. Por favor, pruebe de nuevo.');
        return false;
    }
    
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Sus contraseñas no coinciden. Por favor, pruebe de nuevo.');
        form.inputPassword.focus();
        return false;
    }
        
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");

    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = password.value; //antes estaba con hex_sha512()

    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    conf.value = "";

    // Finally submit the form. 
    form.submit();
    return true;
}