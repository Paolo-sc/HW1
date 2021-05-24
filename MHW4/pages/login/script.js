function displayForm(event){
    if(document.querySelector('#signup').classList.contains('hide'))
        document.querySelector('#signup').classList.remove('hide');
}

document.querySelector('#signup_login').querySelector('a').addEventListener('click',displayForm);

function checkName(event) {

    const input = event.currentTarget;
    
    if (formStatus[input.name] = input.value.length > 0) {
        input.parentNode.parentNode.classList.remove('errornew');
    } else {
        input.parentNode.parentNode.classList.add('errornew');
    }
    checkForm()
}

function jsonCheckUsername(json) {
    // Controllo il campo exists ritornato dal JSON
    if (formStatus.username = !json.exists) {
        document.querySelector('#username').classList.remove('errornew');
    } else {
        document.querySelector('#username span').textContent = "Nome utente già utilizzato";
        document.querySelector('#username').classList.add('errornew');
    }
    checkForm()
}

function jsonCheckEmail(json) {
    // Controllo il campo exists ritornato dal JSON
    if (formStatus.email = !json.exists) {
        document.querySelector('#email').classList.remove('errornew');
    } else {
        document.querySelector('#email span').textContent = "Email già utilizzata";
        document.querySelector('#email').classList.add('errornew');
    }
    checkForm()
}

function fetchResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function checkUsername(event) {
    const input = document.querySelector('#username input');

    if(!/^[a-zA-Z0-9_]{1,15}$/.test(input.value)) {
        input.parentNode.parentNode.querySelector('span').textContent = "Sono ammesse lettere, numeri e underscore. Max. 15";
        input.parentNode.parentNode.classList.add('errornew');
        formStatus.username = false;
    } else {
        fetch("check_username.php?q="+encodeURIComponent(input.value)).then(fetchResponse).then(jsonCheckUsername);
    } 
    checkForm()  
}

function checkEmail(event) {
    const emailInput = document.querySelector('#email input');
    if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(emailInput.value).toLowerCase())) {
        document.querySelector('#email span').textContent = "Email non valida";
        document.querySelector('#email').classList.add('errornew');
        formStatus.email = false;
    } else {
        fetch("check_email.php?q="+encodeURIComponent(String(emailInput.value).toLowerCase())).then(fetchResponse).then(jsonCheckEmail);
    }
    checkForm()
}

function checkPassword(event) {
    const passwordInput = document.querySelector('#password input');
    if (formStatus.password = passwordInput.value.length >= 8) {
        document.querySelector('#password').classList.remove('errornew');
    } else {
        document.querySelector('#password').classList.add('errornew');
    }
    checkForm()
}

function checkConfirmPassword(event) {
    const confirmPasswordInput = document.querySelector('#confirm_password input');
    console.log(document.querySelector('#password input'))
    if (confirmPasswordInput.value == document.querySelector('#password input').value) {
        document.querySelector('#confirm_password').classList.remove('errornew');
    } else {
        document.querySelector('#confirm_password').classList.add('errornew');
    }
    checkForm()
}

function checkForm() {
    // Controlla consenso dati personali
    document.getElementById('submit').disabled = !document.querySelector('.allow input').checked || 
    // Controlla che tutti i campi siano pieni
    Object.keys(formStatus).length !== 7 || 
    // Controlla che i campi non siano false
    Object.values(formStatus).includes(false);
}

const formStatus = {'upload': true};
document.querySelector('#name input').addEventListener('blur', checkName);
document.querySelector('#surname input').addEventListener('blur', checkName);
document.querySelector('#username input').addEventListener('blur', checkUsername);
document.querySelector('#email input').addEventListener('blur', checkEmail);
document.querySelector('#password input').addEventListener('blur', checkPassword);
document.querySelector('#confirm_password input').addEventListener('blur', checkConfirmPassword);