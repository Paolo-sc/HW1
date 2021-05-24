<?php
    include 'auth.php';
    if (checkAuth()) {
        header('Location: home.php');
        exit;
    }

    if (!empty($_POST["usernamel"]) && !empty($_POST["passwordl"]) )
    {
        // Se username e password sono stati inviati
        // Connessione al DB
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        // Preparazione 
        $username = mysqli_real_escape_string($conn, $_POST['usernamel']);
        $password = mysqli_real_escape_string($conn, $_POST['passwordl']);
        // Permette l'accesso tramite email o username in modo intercambiabile
        $searchField = filter_var($username, FILTER_VALIDATE_EMAIL) ? "email" : "username";
        // ID e Username per sessione, password per controllo
        $query = "SELECT id_cliente_privato, username, password FROM cliente_privato WHERE $searchField = '$username'";
        // Esecuzione
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));;
        if (mysqli_num_rows($res) > 0) {
            // Ritorna una sola riga, il che ci basta perché l'utente autenticato è solo uno
            $entry = mysqli_fetch_assoc($res);
            if (password_verify($_POST['passwordl'], $entry['password'])) {

                // Imposto una sessione dell'utente
                $_SESSION["_marinel_username"] = $entry['username'];
                $_SESSION["_marinel_user_id"] = $entry['id'];
                header("Location: ../../mhw1.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        // Se l'utente non è stato trovato o la password non ha passato la verifica
        $error = "Username e/o password errati.";
    }
    else if (isset($_POST["username"]) || isset($_POST["password"])) {
        // Se solo uno dei due è impostato
        $error = "Inserisci username e password.";
    }

?>

<?php
    require_once 'auth.php';

    if (checkAuth()) {
        header("Location: home.php");
        exit;
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["name"]) && !empty($_POST["street"]) &&
    !empty($_POST["surname"]) && !empty($_POST["confirm_password"]) && !empty($_POST["allow"]) && !empty($_POST["number"]) && !empty($_POST["city"]) && !empty($_POST["cap"]) &&!empty($_POST["cf"])){
    $error = array();
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

    
    # USERNAME
    // Controlla che l'username rispetti il pattern specificato
    if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $_POST['username'])) {
        $error[] = "Username non valido";
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        // Cerco se l'username esiste già o se appartiene a una delle 3 parole chiave indicate
        $query = "SELECT username FROM cliente_privato WHERE username = '$username'";
        $res = mysqli_query($conn, $query);
        if (mysqli_num_rows($res) > 0) {
            $error[] = "Username già utilizzato";
        }
    }
    # PASSWORD
    if (strlen($_POST["password"]) < 8) {
        $error[] = "Caratteri password insufficienti";
    } 
    # CONFERMA PASSWORD
    if (strcmp($_POST["password"], $_POST["confirm_password"]) != 0) {
        $error[] = "Le password non coincidono";
    }
    # EMAIL
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = "Email non valida";
    } else {
        $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
        $res = mysqli_query($conn, "SELECT email FROM cliente WHERE email = '$email'");
        if (mysqli_num_rows($res) > 0) {
            $error[] = "Email già utilizzata";
        }
    }
    if (count($error) == 0) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $surname = mysqli_real_escape_string($conn, $_POST['surname']);
        $cf = mysqli_real_escape_string($conn, $_POST['cf']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $address = mysqli_real_escape_string($conn,$_POST['street'].', '.$_POST['city'].', '.$_POST['cap']);

        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "SELECT MAX(id_cliente) FROM cliente";
        $result = $conn->query($sql);
        $result = $result->fetch_array();
        $result = intval($result[0]);
        $result += 1;
        $id = mysqli_real_escape_string($conn,$result);

        $query = "INSERT INTO cliente(id_cliente,telefono,indirizzo,email) VALUES ('$id','$number','$address','$email')";
        mysqli_query($conn,$query);
        $sql = "INSERT INTO cliente_privato (id_cliente_privato,nome,cognome,CF,username,password) VALUES ('$id','$name','$surname','$cf','$username','$password')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION["_marinel_username"] = $_POST["username"];
            $_SESSION["_marinel_user_id"] = mysqli_insert_id($conn);
            mysqli_close($conn);
            header("Location: ../../mhw1.php");
            exit;
        } else {
            $error[] = "Errore di connessione al Database";
        }
    }

    mysqli_close($conn);
}
else if (isset($_POST["username"])) {
    $error = array("Riempi tutti i campi");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <title>MariNel Energia - Accedi</title>
    <script src="script.js" defer="true"></script>
</head>
<body>
    <main>
    <section id="login">
        <h1>MariNel Energia</h1>
        <h2>Accedi</h2>
        <form name="login" method="post">
            <div id="usernamel">
                <div><label for="usernamel">Nome utente o email</label></div>
                <div><input type="text" name="usernamel"></div>
            </div>
            <div id="passwordl">
                <div><label for="passwordl">Password</label></div>
                <div><input type="password" name="passwordl"></div>
            </div>
            <div id="remember">
                <div><input type="checkbox" name="rememberl" value="1"></div>
                <div><label for="rememberl">Ricorda L'accesso</label></div>
            </div>
            <div id="submit_button">
                <input type="submit" value="Accedi come cliente">
            </div>
            <div id="submit_button_staff">
                <input type="submit" value="Accedi come dipendente" class="hide">
            </div>
        </form>
        <div id="signup_login">Sei nuovo?<a>Iscriviti!</a></div>
        <a id="torna" href="../../mhw1.php">Torna alla home</a>
    </section>
    <section id="signup" class="hide">
        <h1>MariNel Energia</h1>
        <h2>Registrati</h2>
        <form name="signup" method="post" enctype="multipart/form-data" autocomplete="off">
            <div id="names">
                <div id="name">
                    <div><label for="name">Nome*</label></div>
                    <div><input type="text" name="name" <?php if(isset($_POST["name"])){echo "value=".$_POST["name"];} ?>></div>
                </div>
                <div id="surname">
                    <div><label for="surname">Cognome*</label></div>
                    <div><input type="text" name="surname" <?php if(isset($_POST["surname"])){echo "value=".$_POST["surname"];} ?>></div>
                </div>
            </div>
            <div id="cf">
                <div><label for="cf">Codice Fiscale</label></div>
                <div><input type="text" name="cf" <?php if(isset($_POST["cf"])){echo "value=".$_POST["cf"];} ?>></div>
            </div>
            <div id="number">
                <div><label for="number">Numero di telefono</label></div>
                <div><input type="text" name="number" <?php if(isset($_POST["number"])){echo "value=".$_POST["number"];} ?>></div>
            </div>
            <div id="street">
                <div><label for="street">Via e numero civico</label></div>
                <div><input type="text" name="street" <?php if(isset($_POST["street"])){echo "value=".$_POST["street"];} ?>></div>
            </div>
            <div id="country">
                <div id="city">
                    <div><label for="city">Città</label></div>
                    <div><input type="text" name="city" <?php if(isset($_POST["city"])){echo "value=".$_POST["city"];} ?>></div>
                </div>
                <div id="cap">
                    <div><label for="cap">CAP</label></div>
                    <div><input type="text" name="cap" <?php if(isset($_POST["cap"])){echo "value=".$_POST["cap"];} ?>></div>
                </div>
            </div>
            <div id="username">
                <div><label for="username">Nome Utente*</label></div>
                <div><input type="text" name="username" <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></div>
                <span></span>
            </div>
            <div id="email">
                <div><label for="email">Email*</label></div>
                <div><input type="text" name="email" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>></div>
                <span></span>
            </div>
            <div id="password">
                <div><label for="password">Password*</label></div>
                <div><input type="password" name="password" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>></div>
            </div>
            <div id="confirm_password">
                <div><label for="confirm_password">Conferma Password*</label></div>
                <div><input type="password" name="confirm_password" <?php if(isset($_POST["confirm_password"])){echo "value=".$_POST["confirm_password"];} ?>></div>
            </div>
            <div id="allow">
                <div><input type="checkbox" name="allow" value="1" <?php if(isset($_POST["allow"])){echo $_POST["allow"] ? "checked" : "";} ?>></div>
                <div><label for="allow">Acconsento al trattamento dei dati personali*</label></div>
            </div>
            <div id="submit_button">
                <input type="submit" value="Registrati" id="submit" disabled>
            </div>
        </form>
    </section>
    </main>
</body>
</html>