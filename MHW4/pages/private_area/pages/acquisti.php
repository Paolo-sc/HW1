<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area-Riservata</title>
    <link rel="stylesheet" href="../private_area.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <script src="script_acquisti.js" defer="true"></script>
</head>
<body>
    <header>
        <nav>
            <div id="logo">
                <a href="../private_area.php">Energia <br>Area Riservata</a>
            </div>
            <div id="navbar">
                <a href="clienti.php">Clienti</a>
                <a href="acquisti.php">Acquisti</a>
            </div>
            <div id="menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </nav>
    </header>
    <section id="tabs">
        <div id="add">
            <strong>Aggiungi articolo</Strong>
        </div>
    </section>
    <section id="add_section" class="hide">
        <div id="add_privato">
            <form action="add_acquisto.php"name="add_articolo" method="post">
                <div id="cliente">
                    <div><label for="cliente">Id Cliente</label></div>
                    <div><input type="text" name="cliente"></div>
                </div>
                <div id="articolo">
                    <div><label for="articolo">Id Articolo</label></div>
                    <div><input type="text" name="articolo"></div>
                </div>
                <div id="fornitore">
                    <div><label for="fornitore">id Fornitore</label></div>
                    <div><input type="text" name="fornitore"></div>
                </div>
                <div id="quantita">
                    <div><label for="quantita">Quantità</label></div>
                    <div><input type="text" name="quantita"></div>
                </div>
                <div id="submit_button">
                    <input type="submit" value="Aggiungi acquisto" name="submit">
                </div>
            </form>
        </div>
</body>
</html>