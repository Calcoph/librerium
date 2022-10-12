function registrar_usuario() {
    let email = document.form_inicio_sesion.email.value
    let usuario = document.form_inicio_sesion.usuario.value
    let contraseña = document.form_inicio_sesion.pswd.value
    let contraseña2 = document.form_inicio_sesion.conf_pswd.value
    let nombre = document.form_inicio_sesion.nombre.value
    let apellido = document.form_inicio_sesion.apellido.value
    let tel = document.form_inicio_sesion.tlf.value
    let fecha = document.form_inicio_sesion.fnacimiento.value
    let dni = document.form_inicio_sesion.dni.value

    let patternTel = /[0-9]{9}/
    let pattern_letras = new RegExp('^[A-Z]+$', 'i'); // Expresión regular de solo letras
     // El regex del email es poco restrictivo a propósito, algunos regex pre-hechos para email no permiten algunos emails válidos
    let regex_email = /.+@.+\..+/ // busca: X@X.X Donde X es cualquier caracter 1 o más veces
    if (nombre == "") {
        window.alert("Introduzca su nombre")
        return
    }
    if (!pattern_letras.test(nombre)) {
        window.alert("Nombre no válido, por favor utilice solo letras");
        return
    }
    if (apellido == "") {
        window.alert("Introduzca su apellido")
        return
    }
    if (!pattern_letras.test(apellido)) {
        window.alert("apellido no válido, por favor utilice solo letras");
        return
    }
    if (!dni_valido(dni)) {
        // Aquí no hay window.alert(), porque las genera dni_valido()
        return
    }
    if (!fecha_valida(fecha)) {
        // Aquí no hay window.alert(), porque las genera fecha_valida()
        return
    }
    if (tel == "") {
        window.alert("Introduzca su teléfono")
        return
    }
    if (!patternTel.test(tel)) {
        window.alert("Teléfono no válido. Tiene que tener este formato: 123456789")
        return
    }
    if (email == "") {
        window.alert("Introduzca su email")
        return
    }
    if (!regex_email.test(email)) {
        window.alert("Email no válido")
        return
    }
    if (contraseña != contraseña2) {
        window.alert("Las contraseñas no coinciden")
        return
    }
    if (contraseña.length > 20) {
        // Porque guardamos las contraseñas en texto plano y en la base de datos se guarda como un Varchar(20)
        window.alert("La contraseña es demasiado larga. Usa como mucho 20 caracteres")
        return
    }
    if (contraseña.length < 3) {
        window.alert("La contraseña es demasiado corta. Usa como mínimo 3 caracteres")
        return
    }
    if (usuario.length > 20) {
        // en la base de datos se guarda como un Varchar(20)
        window.alert("El nombre de usuario es demasiado largo. Usa como mucho 20 caracteres")
        return
    }
    if (usuario.length < 4) {
        window.alert("El nombre de usuario es demasiado corto. Usa como mínimo 4 caracteres")
        return
    }

    // Código obtenido de https://stackoverflow.com/questions/247483/http-get-request-in-javascript
    // Modificaciones: la URL, y la función de callback
    var get_contraseña = new XMLHttpRequest();
    get_contraseña.onreadystatechange = function () {
        if (get_contraseña.readyState == 4 && get_contraseña.status == 200) {
            var contraseña2 = get_contraseña.responseText;
            if (contraseña2 === "") {
                // Si el usuario no tiene contraseña, significa que no está en la base de datos
                document.form_inicio_sesion.submit()
                document.cookie = "username=" + usuario + "; path=/"
            } else {
                window.alert("Ese usuario ya existe")
            }
        }
    }
    let url = window.location.href.split("HTML/register.html")[0];
    get_contraseña.open("GET", url + "PHP/get_contrasena.php/?username=" + usuario, true);
    get_contraseña.send(null);
}

function fecha_valida(fecha) {
    if (fecha == "") {
        window.alert("Introduzca la fecha de nacimiento")
        return false
    }
    let patternFecha = /[0-9]{4}-[0-9]{2}-[0-9]{2}/;
    var mes30 = [4, 6, 9, 11]
    if (!patternFecha.test(fecha)) {
        window.alert("El formato es YYYY-MM-DD (12 de febrero de 2000 = 2000-2-12)")
        return false
    }
    anno = parseInt(fecha.substr(0, 4))
    mes = parseInt(fecha.substr(5, 2))
    dia = parseInt(fecha.substr(8, 2))
    if (anno > new Date().getFullYear()) {
        window.alert("Esta fecha de nacimiento es del futuro")
        return false
    }
    if (anno == 0) {
        window.alert("El año 0 no existe")
        return false
    }
    if (dia <= 0) {
        window.alert("El día 0 no existe (fecha de nacimiento)")
        return false
    }
    if (mes <= 0) {
        window.alert("El mes 0 no existe (fecha de nacimiento)")
        return false
    }
    if (mes > 12) {
        window.alert("El formato es YYYY-MM-DD (12 de febrero de 2000 = 2000-2-12)")
        return false
    }
    if (dia > 31) {
        window.alert("Ningún mes tiene tantos días (fecha de nacimiento)")
        return false
    }
    if (mes == 2) {
        if (anno % 400 == 0 || (anno % 4 == 0 && anno % 100 != 0)) {
            if (dia > 29) {
                alert("Febrero no tiene tantos días (fecha de nacimiento)")
            }
        } else if (dia > 28) {
            alert("Febrero no tiene tantos días (fecha de nacimiento)")
            return false
        }
    }
    if (mes30.includes(mes)) {
        if (dia == 31) {
            window.alert("Ese mes sólo tiene 30 días (fecha de nacimiento)")
            return false
        }
    }
    return true
}

function dni_valido(dni) {
    if (dni != "") {
        window.alert("Introduzca su DNI")
        return false
    }
    expresion_regular_dni = /^\d{8}-[a-zA-Z]$/; //Expresion regular de un DNI
    if (!expresion_regular_dni.test(dni)) {
        window.alert('Dni erroneo, formato no válido. El formato es 12345678-L');
        return false
    }

    numero = dni.substr(0, dni.length - 2);
    letr = dni.substr(dni.length - 1, 1);
    numero = numero % 23;
    letra = 'TRWAGMYFPDXBNJZSQVHLCKET'; // Alfabeto con el cual se calcula la letra del DNI
    letra = letra.substring(numero, numero + 1);
    if (letra != letr.toUpperCase()) {
        alert('Dni erroneo, la letra del DNI no se corresponde');
        return false
    }

    return true
}

function iniciar_sesion() {
    let usuario = document.form_inicio_sesion.usuario.value
    let contraseña = document.form_inicio_sesion.pswd.value

    // Código obtenido de https://stackoverflow.com/questions/247483/http-get-request-in-javascript
    // Modificaciones: la URL, y la función de callback
    var get_contraseña = new XMLHttpRequest();
    get_contraseña.onreadystatechange = function () {
        if (get_contraseña.readyState == 4 && get_contraseña.status == 200) {
            var contraseña2 = get_contraseña.responseText;
            if (contraseña2 === "") {
                window.alert("El ususuario " + usuario + " no existe")
            } else {
                if (contraseña == contraseña2) {
                    console.log("Login successful!")
                    document.cookie = "username=" + usuario + "; path=/"
                    document.form_inicio_sesion.submit()
                } else {
                    console.log(contraseña2)
                    window.alert("Contraseña incorrecta")
                }
            }
        }
    }
    let url = window.location.href.split("HTML/inicio_sesion.html")[0];
    get_contraseña.open("GET", url + "PHP/get_contrasena.php/?username=" + usuario, true);
    get_contraseña.send(null);
}
