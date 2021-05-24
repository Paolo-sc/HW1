<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area-Riservata</title>
    <link rel="stylesheet" href="private_area.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <script src="script.js" defer="true"></script>
</head>
<body>
    <header>
        <nav>
            <div id="logo">
                <a href="private_area.php">Energia <br>Area Riservata</a>
            </div>
            <div id="navbar">
                <a href="pages/clienti.php">Clienti</a>
                <a href="pages/acquisti.php">Acquisti</a>
            </div>
            <div id="menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </nav>
    </header>
    <article id="modale" class="hide"></article>
    <main>
    <section id="profile">
        <div id="image">
            <img src="images/profilo.png">
        </div>
        <div id="profile_data">
            <span>id: 1</span>
            <span>nome: Paolo</span>
            <span>Cognome: Scarentino</span>
            <span>Livello: 2</span>
            <span>data di nascita: 06/07/1999</span>
            <span>codice fiscale: scrpla99l06c342e</span>
            <span>email: paolo.scarentino@gmail.com</span>
            <span>stipendio: 1500€</span>
        </div>
    </section>
    <section id= "procedures">
        <article>
        <h1>Informazioni cliente privato dato il suo id</h1>
        <form action="fetch_information.php" name="procedure1" method="post">
            <div id="procedure_ids">
                <div><label for="id">ID</label></div>
                <div><input type="text" name="id"></div>
            </div>
            <div>
                <input type="submit" value="Ottieni Informazioni" id="submit_1">
            </div>
        </form>
        </article>
        <article>
            <h1>Visualizza tutte le richieste di un derminato id cliente</h1>
            <form name="procedure2" method="post">
                <div id="procedure_ids">
                    <div><label for="id_2">ID</label></div>
                    <div><input type="text" name="id_2" id="input_2"></div>
                </div>
                <div>
                    <input type="submit" value="Ottieni Richieste" id="submit_2">
                </div>
            </form>
        </article>
            <button>Articoli con lo stesso importo lordo</button>
    </section>
    </main>
</body>
</html>